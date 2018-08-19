<?php

namespace vframework\config;

class config {
	public static $config_dir = SYSTEM_ROOT . '/config';
	public static $config_file = array();

	private static function init(){
		$config_general = self::$config_dir . '/config.php';
		$config_app = self::$config_dir . '/APP';
		$config_db = self::$config_dir . '/database.php';

		$config_file = [$config_general, $config_app, $config_db];

		foreach ($config_file as $file) {
			if (!file_exists($file)) {
				die('Config File Not Found! : ' . $file);
			}
		}

		self::$config_file = $config_file;
	}

	public static function get($key){
		$key = strtoupper($key);
		## Prepare
		self::init();
		## Load File Config
		$config_file = self::$config_file;
		require $config_file[0];
		require $config_file[2];


		$key = strtolower($key);
		switch ($key) {
			case 'base_url':
				if (null !== @$config['base_url']){
					$value = $config['base_url'];
				} else {
					$value = BASE_URL;
				}
				break;
			case 'root':
				return ROOT;
				break;
			case 'system_root':
				return SYSTEM_ROOT;
				break;
			case 'routes_dir':
				return self::get('system_root') . '/routes';
				break;
			case 'app_dir':
				return self::get('system_root') . '/app';
				break;
			default:
				$value = @$config[$key];
				break;
		}

		if ($value != '') {
			return $value;
		} else {
			return NULL;
		}
	}

	public function app($key) {
		$key = strtoupper($key);
		## Prepare
		self::init();
		## Load File Config
		$config_file = self::$config_file;

		## LOAD APP CONFIG
		$data_config_app = array();
		if ($file = fopen($config_file[1], 'r')) {
			while (!feof($file)) {
				$line = fgets($file);
					$line = explode('=', $line);
				$data_config_app[$line[0]] = $line[1];
			}
			fclose($file);
		}



		### CHECK DATA ARRAY CONFIG
		foreach ($data_config_app as $c_k => $c_v) {
			if (@$c_k == $key) {
				return $data_config_app[$key];
			}
		}

		return NULL;


	}
}