<?php

namespace vifaframework\config;

use vifaframework\exception\handle as HandleException;

class config {
	private static $config  	= null;
	private static $config_dir 	= SYSTEM_ROOT . '/config';
	private static $config_file	= ['app', 'database'];

	/**
	 * GET CONFIGURATION VALUE
	 *
	 * @param 	(str $type, str $key)
	 * @return 	str $value
	 *
	 */
	public static function get($key)
	{
		if (self::$config == null)
		{
			self::init();		
		}

		$config = self::$config;

		switch ($key) {
			case 'base_url':
				if (null !== @$config['base_url'] and null !== @$config['base_url_protocol'] and $config['base_url'] != "" and $config['base_url_protocol'] != ''){

					$url = explode('/', $config['base_url']);
					foreach ($url as $uri => $uv) {
						if ($uv == '') {
							unset($url[$uri]);
						}
					}

					$config['base_url_protocol'] = str_replace('://', '', $config['base_url_protocol']);

					$value = $config['base_url_protocol'] . "://" . implode('/', $url);
				} else {
					$value = BASE_URL;
				}

				return $value;

				break;			
			default:
				if (isset($config[$key]))
				{
					return $config[$key];
				} else 
				{
					return null;
				}
				break;
		}
	}

	public static function init()
	{

		//dd(self::$config_dir);
		foreach (self::$config_file as $key => $value)
		{
			$config_dir = self::$config_dir;
			$config_file = $config_dir . '/' . $value . '.php';
			if (file_exists($config_file))
			{
				$config = [];
				require $config_file;
				foreach ($config as $key => $value) {
					self::$config[$key] = $value;
				}
			} else 
			{
				try {
					throw new HandleException("Config File Not Found! [$config_file]", get_called_class(), 'Pastikan File COnfig Lengkap.');					
				} catch (HandleException $e) {
					$e->renderError();
				}
			}
		}

		if (!file_exists($config_dir.'/APP')) die("Config File Not Found! [$config_file]");
	}

	public function app($key) {
		$key = strtoupper($key);
		## Prepare
		self::init();
		## Load File Config
		$config_file = self::$config_dir . '/APP';

		## LOAD APP CONFIG
		$data_config_app = array();
		if ($file = fopen($config_file, 'r')) {
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