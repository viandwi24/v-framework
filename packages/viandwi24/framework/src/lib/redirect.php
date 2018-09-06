<?php

namespace vifaframework\lib;

use vifaframework\exception\handle as HandleException;
use vifaframework\config\config;
use vifaframework\router\route;
use vifaframework\router\api as route_api;

class redirect {
	private static function now_old($url){

		$url = explode('/', $url);
		foreach ($url as $u_k => $u_v) {
			if ($u_v == ''){
				unset($url[$u_k]);
			}
		}
		$url = implode('/', $url);
		return config::get('base_url') . '/' . $url;
	}

	public static function route($name) {
		$list_route_name = route::$route_name;

		if (!in_array($name, $list_route_name)) {
			try {
				throw new HandleException('Nama Route ['.$name.'] Tidak Ada!', get_called_class(), 'Cek Apakah Route Yang Dipanggil Telah Diberi Nama Yang Sama!');						
			} catch (HandleException $e){
				$e->renderError();
			}
		} else {
			self::now(route::$route_name_info[$name][1]);
		}
	}

	public static function api($name) {
		$list_route_name = route_api::$route_name;

		if (!in_array($name, $list_route_name)) {
			try {
				throw new HandleException('Nama Route Api ['.$name.'] Tidak Ada!', get_called_class(), 'Cek Apakah Route Api Yang Dipanggil Telah Diberi Nama Yang Sama!');
			} catch (HandleException $e){
				$e->renderError();
			}
		} else {
			self::now(route_api::$route_name_info[$name][1]);
		}
	}

	public static function now($uri = '', $method = 'auto', $code = NULL)
	{
		if ( ! preg_match('#^(\w+:)?//#i', $uri))
		{
			$uri = self::now_old($uri);
		}

		// IIS environment likely? Use 'refresh' for better compatibility
		if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
		{
			$method = 'refresh';
		}
		elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
		{
			if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
			{
				$code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
					? 303	// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
					: 307;
			}
			else
			{
				$code = 302;
			}
		}

		switch ($method)
		{
			case 'refresh':
				header('Refresh:0;url='.$uri);
				break;
			default:
				header('Location: '.$uri, TRUE, $code);
				break;
		}
		exit;
	}
}