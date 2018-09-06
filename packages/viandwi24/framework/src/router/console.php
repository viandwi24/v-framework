<?php

namespace vifaframework\router;

use vifaframework\exception\handle as HandleException;
use vifaframework\config\config;

class console {
	private static $console;

	public static function add($key, $action){
		if (!is_callable($action)) {
			echo "Action Must be Callable!";
			exit();
			die();
		}

		self::$console[$key] = $action;
	}

	public static function route($key, $args) {
		if (isset(self::$console[$key])) {
			echo self::$console[$key]($args);
			return true;
		} else {
			return false;
		}
	}
}