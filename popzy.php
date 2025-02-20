<?php

/**
 * Plugin Name:     Popzy
 * Plugin URI:      
 * Description:     Awesome Popups for WordPress.
 * Author:          Schorpy
 * Author URI:      https://github.com/schorpy
 * License:         GPL-3.0
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:     popzy
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * 
 */



if (!defined('ABSPATH')) {
    exit;
}


define('POPZY_VERSION', '1.0.0');
define('POPZY_PLUGIN_URL', plugin_dir_url(__FILE__));

// load composer
require_once __DIR__ . '/vendor/autoload.php';

//initialize
\Popzy\Core\Plugin::init();

register_activation_hook(__FILE__, [\Popzy\Core\Plugin::class, 'activate']);
register_deactivation_hook(__FILE__, [\Popzy\Core\Plugin::class, 'deactivate']);
register_uninstall_hook(__FILE__, [\Popzy\Core\Plugin::class, 'uninstall']);
