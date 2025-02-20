<?php

namespace Popzy\Core;

use Popzy\Traits\Singleton;

class Plugin
{
    use Singleton;


    public static function init()
    {
        $controllers_path = __DIR__ . '/../Controllers';
        $namespace = 'Popzy\\Controllers\\';

        foreach (scandir($controllers_path) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $class_name = $namespace . pathinfo($file, PATHINFO_FILENAME);
                if (class_exists($class_name)) {
                    (new $class_name())->register();
                }
            }
        }

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
