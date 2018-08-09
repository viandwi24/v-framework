<?php

namespace vframework\kernel;

use vframework\kernel\config;
use vframework\kernel\route;

class error {

	public function make(){
		$arg = func_get_args();
		$error_name = $arg[0];
		unset($arg[0]);
		$arg = implode('[]', $arg);
		$arg = explode('[]', $arg);

		require config::$route_dir . '/error.php';
		route::getError($error_name, $arg);
	}
}