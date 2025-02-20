<?php

namespace Popzy\Api;

use Popzy\Traits\Singleton;
use Popzy\Models\PopupModel;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

class Api extends WP_REST_Controller
{
    use Singleton;

    private function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('rest_api_init', [$this, 'register_popup_meta']);
    }

    public function register_routes()
    {
        register_rest_route('popzy/v1', '/popup', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_popup'],
            'permission_callback' => '__return_true',
        ]);
    }
    public function register_popup_meta()
    {
        register_post_meta('popzy', 'page', [
            'show_in_rest' => true,
            'type'         => 'string',
            'single'       => true,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            }
        ]);
    }



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
