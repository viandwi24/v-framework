<?php

namespace vframework\kernel;

class config {
static $config_dir;
static $route_dir;
static $app_dir;
	
	public function apply(){
		if (file_exists(BOOT_CONFIG)){
			require BOOT_CONFIG;
			self::$config_dir = $boot['config_dir'];
			self::$route_dir = $boot['route_dir'];
			self::$app_dir = $boot['app_dir'];

		} else {
			trigger_error("[error#bx001] BOOT_CONFIG tidak ditemukan!");
			die();
		}
	}

	public function getNow($key = '')
	{
		self::apply();

		if (file_exists(self::$config_dir . '/app.php') and file_exists(self::$config_dir . '/db.php')){
			require self::$config_dir . '/app.php';
			require self::$config_dir . '/db.php';
		} else {
			trigger_error("[error#bx002] CONFIG/APP or CONFIG/DB tidak ditemukan!");
			die();
		}

		if ($key != '' and isset($key)) {
			return @$config[$key];
		}
	}

	public function get($key){
		$key = strtolower($key);

		switch ($key) {
			case 'base_url':
				if (null !== (self::getNow('base_url')) and self::getNow('base_url') != '') {
					return self::getNow('base_url');
				} else {
					return BASE_URL;
				}
				break;
			
			default:
				return self::getNow($key);
				break;
		}
	}
}