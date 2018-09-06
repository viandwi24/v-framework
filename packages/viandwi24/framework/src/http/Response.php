<?php

namespace vifaframework\http;

use vifaframework\config\config;
use vifaframework\exception\handle as HandleException;

class Response {
	public static $middleware;
	private static $response;
	private static $action;
	private static $params;
	private static $url;

	public static function create($url, $action, $params, $middleware)
	{
		self::$middleware  	= $middleware;
		self::$params  	 	= $params;
		self::$action  	 	= $action;
		self::$url  	 	= $url;

		if (!is_callable($action))
		{
			$action = explode('@', $action);
			$controller_class = $action[0];
		}
		$controller_dir = SYSTEM_ROOT . '/app/controllers';

		if (!is_callable($action) and count($action) == 2)
		{
			if (!class_exists($action[0]))
			{
				try {

					if (!file_exists($controller_dir . '/' . $controller_class . '.php'))
					{
						throw new HandleException('Class Controller <b>['.$controller_class.']</b> Tidak Ada.',get_called_class(), 'Pastikan Controller Telah Diletakan di path yang benar.');		
					} else 
					{
						include_once $controller_dir . '/' . $controller_class . '.php';
					}

					if (!class_exists($controller_class)) {
						throw new HandleException('Class Controller <b>['.$controller_class.']</b> Tidak Terload.',get_called_class(), 'Pastikan Controller Telah Terload, Pastikan Controller Tidak Memiliki Namespace.');						
					}

			        $controller = new $controller_class(); 

			        if (!method_exists($controller, $action[1]))
			        {
			        	$method = $action[1];
						throw new HandleException("Method <b>[$method]</b> di controller <b>[$controller_class]</b> tidak ada.",get_called_class(), "Buat method <b>[$method]</b> di controller <b>[$controller_class]</b>");		
			        }

				} catch (HandleException $e) {
					$e->renderError();
				}
			}

			self::$response = function($action, $params){
					 $action = explode('@', $action);
			         $controller_class = $action[0];
			         $controller_dir = SYSTEM_ROOT . '/app/controllers';
					 include_once $controller_dir . '/' . $controller_class . '.php';
			         $controller = new $controller_class(); 
					 echo $controller->{$action[1]}( (object) $params, new Request );
				   };
		} elseif (is_callable($action))
		{
			self::$response = function($action, $params){
					 echo $action((object) $params, new Request);
				   };
		} else {
				try {
					throw new HandleException("Route ini memiliki action yang tidak valid.",get_called_class(), 'Pastikan action pada route ini valid.');
				} catch (HandleException $e) {
					$e->renderError();
				}
		}

	}
	public static function get()
	{
		if (count(Response::$middleware) > 0) {
			foreach (Response::$middleware as $key => $value) {
				$value = 'app\\middleware\\'.$value;
				unset(Response::$middleware[$key]);

				if (!class_exists($value)){
					try {
						throw new HandleException("Middleware <b>[$value]</b> tidak terload.",get_called_class(), 'Pastikan namespace benar, pastikan Middleware tersebut ada.');
					} catch (HandleException $e) {
						$e->renderError();
					}
				}
				
				$middleware = new $value();

				if (!method_exists($middleware, 'handle')){
					try {
						throw new HandleException("Method <b>handle()</b> di Middleware <b>[$value]</b> tidak ada.",get_called_class(), 'Definisikan method handle() di middleware <b>[$value]</b>, Pastikan handle() di set public.');
					} catch (HandleException $e) {
						$e->renderError();
					}
				}


				return $middleware->handle(new Response);
			}
		} else {
			return Response::closure();
		}
	}

	public static function closure()
	{
		$response = Response::$response;
		ob_start();
		$response(self::$action, self::$params);
		$response = ob_get_clean();
		
		echo $response;
	}
}