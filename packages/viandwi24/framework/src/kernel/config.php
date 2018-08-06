<?php

namespace vframework\kernel;

class config {
static $config_dir;
static $route_dir;
static $app_dir;

	public function getNow($key = '')
	{
		if (file_exists(BOOT_CONFIG)){
			require BOOT_CONFIG;
			self::$config_dir = $boot['config_dir'];
			self::$route_dir = $boot['route_dir'];
			self::$app_dir = $boot['app_dir'];

		} else {
			trigger_error("[error#bx001] BOOT_CONFIG tidak ditemukan!");
			die();
		}

		if (file_exists(self::$config_dir . '/app.php')){
			require self::$config_dir . '/app.php';
		} else {
			trigger_error("[error#bx002] CONFIG/APP tidak ditemukan!");
			die();
		}

		if ($key != '') {
			return $config[$key];
		}
	}

	public function get($key){
		$key = strtolower($key);

		switch ($key) {
			case 'base_url':
				return BASE_URL;
				break;
			
			default:
				return self::getNow($key);
				break;
		}
	}
}