<?php

namespace vframework\router;

use vframework\config\config;
use vframework\router\route as route;
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
}