<?php

namespace vifaframework\kernel;

use vifaframework\router\web;
use vifaframework\router\api;
use vifaframework\router\error;

use vifaframework\http\Request;
use vifaframework\http\Response;
use vifaframework\config\config;
use vifaframework\exception\handle as HandleException;

use vifaframework\lib\session;

class boot {
	private $router_dir;

	public function now()
	{
		$this->get_config();
		$this->get_input();
		$this->router_check();
		$this->router_start();
	}

	private function router_check()
	{
		$router_file  	= ['web'];
		$router_dir  	= SYSTEM_ROOT . '/routes';
		$this->router_dir = $router_dir;

		foreach ($router_file as $key => $value) {
			if (!file_exists($router_dir . '/' . $value . '.php'))
			{
					try {
						throw new HandleException("File Routers <b>[$value.php]</b> tidak ada.",get_called_class(), 'Anda kehilangan file routes, reset kembali directori routes dan pastikan file <b>[$value.php]</b> ada.');
					} catch (HandleException $e) {
						$e->renderError();
					}
			}
		}
	}

	private function router_start() 
	{
		$request = new request();

		$web_router  	 			= web::getIstance();
		$web_router::$request_url  	= $request->getRequestUri(true);
		$web_router::$base_url  	= config::get('base_url');


		$web_api  	 			= api::getIstance();
		$web_api::$request_url  = $request->getRequestUri(true);
		$web_api::$base_url  	= config::get('base_url');

		//dd($request->getRequestUri(true));

		### API ROUTES
		require_once $this->router_dir . '/api.php';
		if ($web_api->check())
		{
			$result = $web_api::$result;
			Response::create($result['route'][0], $result['route'][1], $result['params']['value'], $result['route'][3]);
			Response::get();
		} else 
		{
			### WEB ROUTES
			require_once $this->router_dir . '/web.php';

			if ($web_router->check())
			{
				$result = $web_router::$result;
				Response::create($result['route'][0], $result['route'][1], $result['params']['value'], $result['route'][3]);

				//dd($result['route'][3]);

				Response::get();
			} else 
			{
				error::make('404');
			}
		}

	}

	private function get_config(){
		config::init();
	}
	private function get_input(){
		if ($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$_POSTNYA = $_POST;

			$remove_input = ['_csrf', '_put','_patch','_option', '_delete'];
			foreach ($remove_input as $key => $value) {
				unset($_POSTNYA[$value]);
			}

			if (count($_POSTNYA) > 0) {
				$app = session::get('__app__');
				$app['last_input'] = $_POSTNYA;
				session::set('__app__', $app);
			} else {
				$app = session::get('__app__');
				$app['last_input'] = [];
				session::set('__app__', $app);
			}
		} 

	}
}