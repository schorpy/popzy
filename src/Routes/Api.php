<?php

/**
 * REST API Routes for Popzy Plugin
 *
 * @package Popzy
 * @subpackage Routes
 */

namespace Popzy\Routes;

if (! defined('ABSPATH')) {
    exit;
}

use Popzy\Core\Route;

Route::prefix(
    POPZY_ROUTE_PREFIX,
    function (Route $route) {
        /**
         * GET /popup
         * 
         * Retrieves popup data based on type and ID.
         * 
         * @param string type The type of popup to retrieve.
         * @param int    id   The popup ID.
         */
        $route->get('/popup', '\Popzy\Controllers\PopupController@get_popup');

        //admin
        //$route->get('/popup', '\Popzy\Controllers\PopupController@get_popup','admin');

    }
);
