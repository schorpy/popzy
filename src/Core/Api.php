<?php

namespace Popzy\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Popzy\Traits\Base;
use Popzy\Core\Config;

class Api {

	use Base;

	
	public function init() {
		Config::set_route_file( POPZY_PLUGIN_DIR . '/src/Routes/Api.php' )
			->set_namespace( 'Popzy\Api' )
			->init();
	}
}