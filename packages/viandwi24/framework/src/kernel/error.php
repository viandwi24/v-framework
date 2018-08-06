<?php

namespace vframework\kernel;

use vframework\kernel\config;

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
}