<?php

namespace Popzy\Controllers;

if (! defined('ABSPATH')) exit;

use Popzy\Models\PopupModel;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;


class PopupController
{
    public function get_popup(WP_REST_Request $request)
    {
        $type = sanitize_text_field($request->get_param('type'));
        $id = absint($request->get_param('id'));
        if (empty($type)) {
            return new WP_REST_Response(['message' => 'type parameter is required'], 400);
        }

        $popup = PopupModel::get_popup($type, $id);
        if ($popup) {
            return new WP_REST_Response($popup, 200);
        }

        return new WP_REST_Response(['message' => 'Popup not found'], 404);
    }

   
}
