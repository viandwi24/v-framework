<?php

namespace vframework\kernel;

use vframework\kernel\config;
use vframework\kernel\route;

class router {

	function boot()
	{
		$this->processRoute();
	}

	public function processRoute(){
		$reqUrl = $this->GetReqUrl(true);
		$admin_url = config::get("admin_url");

		$this->routeNow();
	}

	##################################################################################################
	protected function routeNow(){
		##SET ROUTE
		require config::$route_dir . '/app.php';
		## LAKUKAN ROUTE SEKARANG
		route::now();
	}

	public function GetReqUrl($return_array = false)
	{
		$reqUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$reqUrl = explode('/', $reqUrl);
		foreach ($reqUrl as $rU => $rU_v) {
			if ($rU_v == '') {
				unset($reqUrl[$rU]);
			}
		}
		$reqUrl = implode('/', $reqUrl);

		$baseUrl = config::get("base_url");
		$baseUrl = str_replace('http://', '', $baseUrl);
		$baseUrl = str_replace('https://', '', $baseUrl);

		$reqUrl = str_replace( $baseUrl, '', $reqUrl);

		
		$reqUrl = explode('/', $reqUrl);
		foreach ($reqUrl as $rU => $rU_v) {
			if ($rU_v == '') {
				unset($reqUrl[$rU]);
			}
		}
		$reqUrl = implode('/', $reqUrl);


		if ($return_array) {
			return explode('/', $reqUrl);
		} else {
			return $reqUrl;
		}
	}
}