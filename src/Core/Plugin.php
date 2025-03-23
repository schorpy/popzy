<?php

namespace Popzy\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Popzy\Core\Api;
use Popzy\Helpers\Assets;
use Popzy\Helpers\Code;
use Popzy\Helpers\CPTs;
use Popzy\Helpers\Metaboxes;


class Plugin
{

    public static function init()
    {
       
        Code::getInstance();
        Api::get_instance()->init();
        Assets::getInstance();
        CPTs::getInstance();
        Metaboxes::getInstance();
        \Popzy\Helpers\Cache::init();
    }
    public static function activate() {}
    public static function deactivate()
    {
        \Popzy\Helpers\Cache::deactivate();
    }
    public static function uninstall()
    {
        \Popzy\Helpers\Cache::uninstall();
    }
}
