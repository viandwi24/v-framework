<?php

namespace vframework\kernel;

use vframework\kernel\config;
use vframework\kernel\route;

class error {
	static $error_view_dir;

	public function force($val){
		if (@self::$error_view_dir == '') {self::$error_view_dir = config::$app_dir . '/views/error';}

		switch ($val) {
			case 404:
				$description = 'Page Not Found!';
				require self::$error_view_dir . '/404.php';
				break;
			
			default:
				# code...
				break;
		}
		die();
	}

	public function custom(){
		$arg = func_get_args();
		$error_name = $arg[0];
		unset($arg[0]);
		$arg = implode('[]', $arg);
		$arg = explode('[]', $arg);

		require config::$route_dir . '/error.php';
		route::getError($error_name, $arg);
	}
}