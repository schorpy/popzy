<?php
namespace Popzy\Helpers;

if (! defined('ABSPATH')) exit;

use Popzy\Traits\Singleton;

class Code
{
    use Singleton;
    
    private function __construct()
    {
        add_action('wp_footer',  [$this, 'add_popup_footer']);
    }

    public function add_popup_footer()
    {
        echo '<div id="popup-root"></div>';
    }
}