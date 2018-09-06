<?php

namespace vifaframework\router;

use vifaframework\exception\handle as HandleException;

class web {    
	/**
	 * INSTANCE
	 *
	 * @var $instance = null
	 *
	 */
	private static $instance = null;

	/**
	 * ROUTE
	 *
	 * @var $route = array() | key : get, post, put, pacth, option, delete
	 *
	 */
	private static $route = [
								'GET' => [], 'POST' => [], 'PUT' => [],
								'PATCH' => [], 'OPTION' => [], 'DELETE' => [],
							];
	
	/**
	 * Temprorary
	 *
	 * @var $temp_... = null
	 *
	 */
	private static $temp_prefix = null;
	private static $temp_route_method = null;

	private static $temp_middleware_prefix = false;
	private static $temp_middleware_prefix_route = [];
	private static $temp_middleware_prefix_value = [];
	private static $temp_count_route;

	
	/**
	 * ROUTE 2 (Key = name)
	 *
	 * @var $route_name = array() | [$key] name, [$value] route info
	 *
	 */
	private static $route_name = [];
	
	/**
	 * PARAMETERS SEGMENT URL
	 *
	 * @var $params = array() | [$key] name, [$value] value url
	 *
	 */
	private static $params = [];
	
	/**
	 * REQUEST URL
	 *
	 * @var $request_url = Request->getRequestUri() | #return array or str from #param false
	 *
	 */
	public static $request_url = [];
	
	/**
	 * BASE URL
	 *
	 * @var $base_url = config::get('base_url') 
	 *
	 */
	public static $base_url = '';
	public static $result = null;

	
	/**
	 * SINGLETON ISTANCE
	 *
	 * @return class
	 *
	 */
	public static function getIstance(){
		if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
	}

	/**
	 * SET ROUTE 
	 *
	 * @param 	(method route, $args)
	 * args : 	0 => uri
	 * 	 	 	1 => action (closure function or string)
	 *
	 */
	private static function set_route($method, $args)
	{
		self::getIstance()::$temp_route_method = $method;
		$args[0] = self::clean_url($args[0]);


		if (self::getIstance()::$temp_prefix != null) {
			$args[0] = self::getIstance()::$temp_prefix.$args[0];
		}
		$args[0] = self::create_pattern($args[0]);
		$args[0] = self::clean_url( $args[0] );
		
		array_push(self::getIstance()::$route[$method], [$args[0], $args[1], '', []]);
	}

	private static function create_pattern($uri)
	{
		$uri = explode('/', $uri);
		$berparam = false;
		$method = self::$temp_route_method;

		self::getIstance()::$params[$method][count(self::getIstance()::$route[$method])]['value'] = [];

		foreach ($uri as $key => $value) {
			preg_match("/{(.*)\?}/", $value, $output_array);
			if (count($output_array) == 2)
			{
				$uri[$key] = '[?]';
				self::getIstance()::$params[$method][count(self::getIstance()::$route[$method])]['value'][$output_array[1]] = @self::$request_url[$key];
				$berparam = true;
			} else 
			{

				preg_match("/{(.*)}/", $value, $output_array);
				if (count($output_array) == 2)
				{
					$uri[$key] = '[]';
				$method = self::$temp_route_method;
				self::getIstance()::$params[$method][count(self::getIstance()::$route[$method])]['value'][$output_array[1]] = @self::$request_url[$key];
				}
			}

		}


		self::getIstance()::$params[$method][count(self::getIstance()::$route[$method])]['opt_bool'] = $berparam;
		return implode('/', $uri);
	}


	/**
	 * ROUTING
	 * 
	 * @param 	(uri route, action route)
	 * @return 	static
	 * for get(), post(), put(), patch(), option(), delete()
	 */
	public static function get()
	{
		$args = func_get_args();
		self::getIstance()::set_route('GET', $args);
		return new static;
	}

	public static function post()
	{
		$args = func_get_args();
		self::getIstance()::set_route('POST', $args);
		return new static;
	}

	public static function put()
	{
		$args = func_get_args();
		self::getIstance()::set_route('PUT', $args);
		return new static;
	}

	public static function patch()
	{
		$args = func_get_args();
		self::getIstance()::set_route('PATCH', $args);
		return new static;
	}

	public static function option()
	{
		$args = func_get_args();
		self::getIstance()::set_route('OPTION', $args);
		return new static;
	}

	public static function delete()
	{
		$args = func_get_args();
		self::getIstance()::set_route('DELETE', $args);
		return new static;
	}

	/**
	 * ROUTING - Prefix
	 * 
	 * @param 	(str $prefix, closure $func)
	 * @return 	static
	 *
	 */

	public static function prefix($prefix, $func)
	{
		self::$temp_middleware_prefix = true;

		if (is_callable($func)) 
		{
			$methodnya = ['GET','POST','PATCH','PUT','OPTION','DELETE'];
			foreach ($methodnya as $key => $value) {
					$count_route[$value] = count(self::$route[$value]);
			}


			self::getIstance()::$temp_prefix = self::clean_url($prefix) . '/';
			$func();


			foreach ($methodnya as $key => $value) {
					$new_count_route[$value] = count(self::$route[$value]);
			}

			self::$temp_count_route = ['old' => $count_route, 'new' => $new_count_route];

		}
		
		self::getIstance()::$temp_prefix = null;
		return new static;
	}


	/**
	 * CHANGE NAME
	 * 
	 * @param 	(str name)
	 * @return 	static
	 *
	 */
	public static function name($name)
	{	
		$method = self::getIstance()::$temp_route_method;
		if ($method != null)
		{
			if (!isset(self::getIstance()::$route_name[$name])){
				self::getIstance()::$route[$method][count(self::getIstance()::$route[$method])-1][2] = $name;
				self::getIstance()::$route_name[] = $name;
			} else {
				try {
					throw new HandleException("Nama controller [$name] sudah di gunakan.", get_called_class(), "Gunakan nama yang lain.");
					
				} catch (HandleException $e){
					$e->renderError();
				}
			}
		} else {
			try {
				throw new HandleException("Tidak ada route yang diselect.", get_called_class(), "Gunakan nama yang lain.");
					
			} catch (HandleException $e){
					$e->renderError();
			}
		}
		return new static;
	}


	/**
	 * CHECK ROUTE
	 * 
	 * @return 	boolean
	 *
	 */
	public static function check()
	{

		if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_put'])) {
			if (!self::search('PUT')) {
				return false;
			} else {
				return true;
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_delete'])) {
			if (!self::search('DELETE')) {
				return false;
			} else {
				return true;
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_patch'])) {
			if (!self::search('PATCH')) {
				return false;
			} else {
				return true;
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_option'])) {
			if (!self::search('OPTION')) {
				return false;
			} else {
				return true;
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
			if (!self::search('POST')){
				if (!self::search('GET')){
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		} else {
			if (!self::search('GET')){
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * SEARCH ROUTE
	 * 
	 * @return 	boolean
	 *
	 */
	private static function search($method)
	{
		$route 	 	 	= self::$route[$method];
		$request_url 	= self::$request_url;
		$params 	 	= @self::$params[$method];

		foreach ($route as $key => $value) {
			$sama = true;
			$route_url = explode('/', $value[0]);
			$params 	 	= self::$params[$method][$key];

			if (count($request_url) == count($route_url) or $params['opt_bool'] == true)
			{
				foreach ($route_url as $ru_key => $ru_value) {
					if ($ru_value == @$request_url[$ru_key]) {
					} elseif ($ru_value == '[]') {
					} elseif ($ru_value == '[?]') {
					} else {
						$sama = false;
					}
				}
			} else
			{
				$sama = false;
			}

			if ($sama)
			{
				self::getIstance()::$result['route'] = $route[$key];
				self::getIstance()::$result['method'] = $method;
				self::getIstance()::$result['params'] = $params;
				return true;
				break;
			}
		}
		
		return false;
	}

	/**
	 * GET METHOD
	 * 
	 * @return 	str
	 *
	 */
	public static function get_method()
	{
	}

	/**
	 * SET MIDDLEWARE
	 * 
	 * @param 	(str,str,str..)
	 * @return 	static
	 *
	 */
	public static function middleware()
	{
		$args = func_get_args();
		$method = self::getIstance()::$temp_route_method;


		if (self::$temp_middleware_prefix)
		{
			$count_route = self::$temp_count_route;

			$methodnya = ['GET','POST','PATCH','PUT','OPTION','DELETE'];
			foreach ($methodnya as $key => $value) {
				$change_route[$value] = $count_route['new'][$value] - $count_route['old'][$value];
			}

			foreach ($methodnya as $key => $value) {

				for ($i=1; $i < $change_route[$value]+1; $i++) { 
					$result =  $count_route['old'][$value] + ($i-1);
					self::getIstance()::$route[$value][$result][3] = $args;
				}
			}
			
			self::$temp_middleware_prefix = false;
			//return new static;
		}


		if ($method != null)
		{
			foreach ($args as $key => $value) {
				array_push(self::getIstance()::$route[$method][count(self::getIstance()::$route[$method])-1][3], $value);
			}
		}
		self::$temp_middleware_prefix = false;

		return new static;
	}

	/**
	 * CLEAN URL
	 * 
	 * @param 	(str uri)
	 * @return 	str
	 *
	 */
	private static function clean_url($uri)
	{
		$uri = explode('/', $uri);
		foreach ($uri as $key => $value) {
			if ($value == '') {
				unset($uri[$key]);
			}
		}
		return implode('/', $uri);
	}
}