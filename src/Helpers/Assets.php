<?php

namespace Popzy\Helpers;

if (! defined('ABSPATH')) exit;

use Popzy\Traits\Singleton;

class Assets
{
    use Singleton;

    private function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_enqueue_scripts', [$this, 'localize_popup_script']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_assets']);
    }


    public function admin_enqueue_assets()
    {
        wp_enqueue_style('popzy-style', POPZY_PLUGIN_URL . 'assets/css/style.css', [], POPZY_VERSION);
        wp_enqueue_style('popzy-style-admin', POPZY_PLUGIN_URL . 'assets/css/admin.min.css', [], POPZY_VERSION);

        // Select2
        wp_register_script('select2', POPZY_PLUGIN_URL . 'assets/js/select2.min.js', ['jquery'], '4.0.13', true);
        wp_enqueue_script('select2');
        wp_enqueue_style('select2-css', POPZY_PLUGIN_URL . 'assets/css/select2.min.css', [], '4.0.13');

        // Admin Script
        wp_register_script('popzy-admin-js', POPZY_PLUGIN_URL . 'assets/js/admin.min.js', ['wp-element'], POPZY_VERSION, true);
        wp_enqueue_script('popzy-admin-js');
    }

    public function enqueue_assets()
    {
        wp_enqueue_style('popzy-style', POPZY_PLUGIN_ASSETS_URL . '/css/style.css', [], POPZY_VERSION);
        wp_enqueue_style('popzy-style-frontend', POPZY_PLUGIN_ASSETS_URL . '/css/frontend.min.css', [], POPZY_VERSION);

        wp_register_script('popzy-js', POPZY_PLUGIN_ASSETS_URL . '/js/frontend.min.js', ['wp-element'], POPZY_VERSION, true);
        wp_enqueue_script('popzy-js');
    }


    public function localize_popup_script()
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
}
