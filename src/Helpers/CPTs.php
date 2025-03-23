<?php

namespace Popzy\Helpers;

if (! defined('ABSPATH')) exit;

use Popzy\Traits\Singleton;

class CPTs
{
    use Singleton;

    private function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
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
}
