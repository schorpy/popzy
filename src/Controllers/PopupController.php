<?php

namespace Popzy\Controllers;

use Popzy\Api\Api;
use Popzy\Helpers\Cache;

class PopupController
{
    public function register()
    {
        Api::getInstance();
        add_action('init', [$this, 'register_post_type']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_enqueue_scripts', [$this, 'localize_popup_script']);
        add_action('wp_footer',  [$this, 'add_popup_footer']);

        //admin side
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_assets']);
        add_action('add_meta_boxes', [$this, 'add_popup_meta_box']);
        add_action('save_post', [$this, 'save_popup_meta']);
        add_action('admin_notices', [$this, 'popzy_admin_notice']);
    }

    public function register_post_type()
    {
        register_post_type('popzy', [
            'labels' => [
                'name'          => 'Popzy',
                'singular_name' => 'Popzy',
                'add_new'       => 'Add New Popup',
                'add_new_item'  => 'Create New Popup',
                'all_items'     => 'All Popups'
            ],
            'public'      => false,
            'show_ui'     => true,
            'show_in_rest' => true,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => true,
            'supports'    => ['title', 'editor'],
            'menu_icon'    => POPZY_PLUGIN_URL . 'assets/icon-16x16.png',

        ]);
    }

    public function admin_enqueue_assets()
    {
        wp_enqueue_style('popzy-style', POPZY_PLUGIN_URL . 'assets/css/style.css', [], POPZY_VERSION);
        wp_enqueue_style('popzy-style-admin', POPZY_PLUGIN_URL . 'assets/css/admin.min.css', [], POPZY_VERSION);
        //Select2 admin
        wp_enqueue_script('select2',  POPZY_PLUGIN_URL . 'assets/js/select2.min.js', ['jquery'], '4.0.13', true);
        wp_enqueue_style('select2-css', POPZY_PLUGIN_URL . 'assets/css/select2.min.css', [], '4.0.13');
        wp_enqueue_script('popzy-admin-js', POPZY_PLUGIN_URL . 'assets/js/admin.min.js', ['wp-element'], POPZY_VERSION, true);
    }
    public static function enqueue_assets()
    {
        wp_enqueue_style('popzy-style', POPZY_PLUGIN_URL . 'assets/css/style.css', [], POPZY_VERSION);
        wp_enqueue_style('popzy-style-frontend', POPZY_PLUGIN_URL . 'assets/css/frontend.min.css', [], POPZY_VERSION);
        wp_enqueue_script('popzy-js', POPZY_PLUGIN_URL . 'assets/js/frontend.min.js', ['wp-element'], POPZY_VERSION, true);
    }

    public static function localize_popup_script()
    {
        global $post;

        if (wp_script_is('popzy-js', 'enqueued')) {
            wp_localize_script('popzy-js', 'popzy', [
                'post_id'   => isset($post) ? $post->ID : null,
                'post_type' => isset($post) ? get_post_type($post->ID) : null,
                'rest_url'  => esc_url_raw(rest_url('popzy/v1')),
                'nonce'     => wp_create_nonce('wp_rest'),
                'is_home'   => is_home(),
                'is_front'  => is_front_page(),
                'is_logged_in' => is_user_logged_in()
            ]);
        }
    }

    public function add_popup_meta_box()
    {
        add_meta_box(
            'popup_page_meta_box',
            'Popzy Setting',
            [$this, 'popup_meta_box_callback'],
            'popzy', //post type
            'advanced', //bottom location
            'high'
        );
    }

    public function popup_meta_box_callback($post)
    {

        $popup_target = get_post_meta($post->ID, 'popzy_target', true);
        $popup_settings = get_post_meta($post->ID, 'popzy_settings', true);
        $title_value = '';
        $delay_value = '';


        if (!empty($popup_settings)) {

            if (is_array($popup_settings)) {
                $title_value = $popup_settings['title'] ?? '';
                $delay_value = $popup_settings['delay'] ?? '';
            }
        }

        $pages = get_pages();
        $posts = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => -1,
        ]);

        // Nonce field for safety
        wp_nonce_field('popzy_meta_box_nonce', 'popzy_meta_box_nonce');

        // Data for view
        $data['options'] = [
            ['id' => 'homepage', 'text' => 'Homepage'],
            ['id' => 'all_pages', 'text' => 'All Pages'],
            ['id' => 'all_posts', 'text' => 'All Posts'],
        ];

        // "Specific Post"
        if (!empty($posts)) {
            $data['options'][] = [
                'id'    => 'specific_post',
                'text'  => 'Specific Post',
                'group' => array_map(fn($post) => [
                    'type'   => 'post',
                    'id'   => (string) $post->ID,
                    'text' => esc_html($post->post_title),
                ], $posts),
            ];
        }

        // "Specific Page"
        if (!empty($pages)) {
            $data['options'][] = [
                'id'    => 'specific_page',
                'text'  => 'Specific Page',
                'group' => array_map(fn($page) => [
                    'type'   => 'page',
                    'id'   => (string) $page->ID,
                    'text' => esc_html($page->post_title),
                ], $pages),
            ];
        }


        $data['selected'] = $popup_target;
        $data['show_title'] = $title_value;
        $data['delay'] = $delay_value;

        \Popzy\Core\View::Render('Admin.tab', $data);
    }


    public function save_popup_meta($post_id)
    {
        // Security check
        if (!isset($_POST['popzy_meta_box_nonce'])) {
            return;
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['popzy_meta_box_nonce']));
        if (!wp_verify_nonce($nonce, 'popzy_meta_box_nonce')) {
            return;
        }


        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }


        $settings = get_post_meta($post_id, 'popzy_settings', true);
        $settings = is_array($settings) ? $settings : [];

        $settings['title'] = sanitize_text_field(wp_unslash($_POST['popzy_option']['title'] ?? ''));
        $settings['delay'] = sanitize_text_field(wp_unslash($_POST['popzy_trigger']['delay'] ?? ''));


        update_post_meta($post_id, 'popzy_settings', $settings);


        if (!empty($_POST['popzy_target']['select'])) {
            $previous_popup_target = get_post_meta($post_id, 'popzy_target', true);
            $value = sanitize_text_field(wp_unslash($_POST['popzy_target']['select']));

            update_post_meta($post_id, 'popzy_target', sanitize_text_field(wp_unslash($_POST['popzy_target']['select'])));

            Cache::delete_cache($previous_popup_target);
        }
    }


    public function add_popup_footer()
    {
        echo '<div id="popup-root"></div>';
    }
    function popzy_admin_notice($post)
    {
        return;
    }
}
