<?php

namespace vframework\router;

use vframework\config\config;
use vframework\router\route;
use vframework\exception\handle as HandleException;


class kernel {
	public function boot(){

		$routes_dir = config::get('routes_dir');

		try {
			$routes_file = ['web', 'console', 'api', 'error'];

			foreach ($routes_file as $file) {
				if (!file_exists($routes_dir . '/' . $file . '.php')) {
					throw new HandleException();	
					break;
				}
			}

			###### ROUTE WEB.PHP

			require $routes_dir . '/web.php';			
			route::now();

		} catch (HandleException $e){
			$e->renderError('Tidak Dapat Menemukan File Routes <b>'.$file.'</b>!', 'system/routes/<b>'.$file.'.php</b>', 'Perbaiki File Routes Yang Hilang Menggunakan Vifa Console : php vifa reset:routes <b>'.$file.'</b>');
		}

	}
	######
	public static function getReqUrl($return_array = false){
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

		$reqUrl = explode('?', $reqUrl);

		try {
			if (count($reqUrl) == 1 or count($reqUrl) == 2) {}else{ 
				throw new HandleException(); 
			}
			
		} catch (HandleException $e) {
			$e->renderError('Terdapat Karakter Yang Tidak Diperbolehkan Di Tulis Di URL!', '-', 'Jangan Menambahkan Karakter Yang Tidak Diperbolehkan Di URL :<b> ?  {  }  |</b>');
		}

		$reqUrl = $reqUrl[0];

		if ($return_array) {
			return explode('/', $reqUrl);
		} else {
			return $reqUrl;
		}
	}
}