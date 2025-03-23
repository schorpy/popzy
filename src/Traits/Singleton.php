<?php

namespace Popzy\Traits;

if (! defined('ABSPATH')) exit;

trait Singleton
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
