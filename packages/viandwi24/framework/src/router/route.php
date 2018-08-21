<?php

namespace vframework\router;

use vframework\router\kernel;
use vframework\exception\handle as HandleException;
use vframework\config\config;
use vframework\request\HTTPRequests as Request;
use vframework\base\error;

class route {
	private static $route_get = array();
	private static $route_post = array();
	private static $route_put = array();
	private static $route_delete = array();
	private static $route_patch = array();
	private static $route_option = array();
	
	public static $route_name = array();
	public static $route_name_info = array();

	private static $temp_route_set_method = NULL;
	private static $temp_route_prefix = NULL;


	public static function get($route_url, $route_action){
		if (!self::$temp_route_prefix == NULL) {$route_url = self::$temp_route_prefix.'/'.$route_url;}
		array_push(self::$route_get, [self::clean_url($route_url), $route_action, ''] );
		self::$temp_route_set_method = "get";
		return new static;
	}
	public static function post($route_url, $route_action){
		if (!self::$temp_route_prefix == NULL) {$route_url = self::$temp_route_prefix.'/'.$route_url;}
		array_push(self::$route_post, [ self::clean_url($route_url), $route_action, ''] );
		self::$temp_route_set_method = "post";
		return new static;
	}
	public static function put($route_url, $route_action){
		if (!self::$temp_route_prefix == NULL) {$route_url = self::$temp_route_prefix.'/'.$route_url;}
		array_push(self::$route_put, [self::clean_url($route_url), $route_action, ''] );
		self::$temp_route_set_method = "put";
		return new static;
	}
	public static function delete($route_url, $route_action){
		if (!self::$temp_route_prefix == NULL) {$route_url = self::$temp_route_prefix.'/'.$route_url;}
		array_push(self::$route_delete, [self::clean_url($route_url), $route_action, ''] );
		self::$temp_route_set_method = "delete";
		return new static;
	}
	public static function patch($route_url, $route_action){
		if (!self::$temp_route_prefix == NULL) {$route_url = self::$temp_route_prefix.'/'.$route_url;}
		array_push(self::$route_patch, [self::clean_url($route_url), $route_action, ''] );
		self::$temp_route_set_method = "patch";
		return new static;
	}
	public static function option($route_url, $route_action){
		if (!self::$temp_route_prefix == NULL) {$route_url = self::$temp_route_prefix.'/'.$route_url;}
		array_push(self::$route_option, [self::clean_url($route_url), $route_action, ''] );
		self::$temp_route_set_method = "option";
		return new static;
	}

	public static function name($name){
		if (self::$temp_route_set_method != NULL) {

			if (in_array($name, self::$route_name)){
				try {
					throw new HandleException();						
				} catch (HandleException $e){
					$e->renderError('Nama Route ['.$name.'] Sudah Ada!', get_called_class(), 'Gunakan Nama Lain!');
				}
			}

			$method = self::$temp_route_set_method;

			if ($method == 'get') { 
				self::$route_get[count(self::$route_get)-1][2] = $name;
				$url = self::$route_get[count(self::$route_get)-1][0];
				$action = self::$route_get[count(self::$route_get)-1][1];
				
			} elseif ($method == 'post') { 
				self::$route_post[count(self::$route_post)-1][2] = $name;
				$url = self::$route_post[count(self::$route_post)-1][0];
				$action = self::$route_post[count(self::$route_post)-1][1];

			} elseif ($method == 'put') { 
				self::$route_put[count(self::$route_put)-1][2] = $name;
				$url = self::$route_put[count(self::$route_put)-1][0];
				$action = self::$route_put[count(self::$route_put)-1][1];

			} elseif ($method == 'delete') { 
				self::$route_delete[count(self::$route_delete)-1][2] = $name;
				$url = self::$route_delete[count(self::$route_delete)-1][0];
				$action = self::$route_delete[count(self::$route_delete)-1][1];

			} elseif ($method == 'patch') { 
				self::$route_patch[count(self::$route_patch)-1][2] = $name;
				$url = self::$route_patch[count(self::$route_patch)-1][0];
				$action = self::$route_patch[count(self::$route_patch)-1][1];

			} elseif ($method == 'option') { 
				self::$route_option[count(self::$route_option)-1][2] = $name;
				$url = self::$route_option[count(self::$route_option)-1][0];
				$action = self::$route_option[count(self::$route_option)-1][1];
			} else {
				return new static;
			}

			array_push(self::$route_name, $name);
			self::$route_name_info[$name] = [$method, $url, $action];


		}

		return new static;
	}

	public static function prefix($prefix, $route) {
		$prefix = self::clean_url($prefix);

		self::$temp_route_prefix = $prefix;

		if (is_callable($route)) {
			$route();
		}
		
		self::$temp_route_prefix = NULL;
		return new static;
	}

	private static function dd($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}
	public static function tes(){
		self::dd(self::$route_name_info);
	}
	public static function now(){

		if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_put'])) {
			if (!self::route_start('PUT')) {
				error::make('404');
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_delete'])) {
			if (!self::route_start('DELETE')) {
				error::make('404');
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_patch'])) {
			if (!self::route_start('PATCH')) {
				error::make('404');
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['_option'])) {
			if (!self::route_start('OPTION')) {
				error::make('404');
			}
		} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
			if (!self::route_start('POST')){
				if (!self::route_start('GET')){
					error::make('404');
				}	
			}
		} else {
			if (!self::route_start('GET')){
				error::make('404');
			}	
		}
	}
	########################################
	private static function route_start($method){
		switch ($method) {
			case 'GET':
				$route = self::$route_get;
				break;

			case 'POST':
				$route = self::$route_post;
				break;

			case 'PUT':
				$route = self::$route_put;
				break;

			case 'DELETE':
				$route = self::$route_delete;
				break;

			case 'PATCH':
				$route = self::$route_patch;
				break;

			case 'OPTION':
				$route = self::$route_option;
				break;

			default:
				$route = self::$route_get;
				break;
		}

		$params = array();

		foreach ($route as $r_k => $r_v) {
		$route_req_url 		= explode('/', $r_v[0]);
		$request 			= new Request();
		$request_url 		= $request->getReqUrl(true);


			$sama 				= true;
			$url_berparam_opt		= false;


			foreach ($route_req_url as $rr_k => $rr_v) {
				$temp_check_rru = substr($rr_v, strlen($rr_v)-2);
				$temp_check_rru = trim($temp_check_rru, '}');

				if ($temp_check_rru == '?') $url_berparam_opt = true;
			}


			if (count($route_req_url) == count($request_url) or $url_berparam_opt) {
				foreach ($route_req_url as $rr_k => $rr_v) {
						$temp_check_url_param_first = substr($rr_v, 0 , 1);
						$temp_check_url_param_end = substr($rr_v, strlen($rr_v)-1 , 1);
						$temp_check_url_param_opt = substr($rr_v, strlen($rr_v)-2 , 1);

					if ($rr_v == @$request_url[intval($rr_k)]){
					} elseif ($url_berparam_opt and $temp_check_url_param_opt == '?') {
						$params[substr($rr_v, 1, strlen($rr_v)-3)] = @$request_url[intval($rr_k)];
					} elseif ($temp_check_url_param_first == '{' and $temp_check_url_param_end == '}') {
						$params[substr($rr_v, 1, strlen($rr_v)-2)] = @$request_url[intval($rr_k)];
					} else {
						$sama = false;
					}
				}
			} else {
				$sama = false;
			}


			if ($sama) {
				#### CALLL ACTION
				$controller_dir = config::get('app_dir') . '/controllers';

				if (is_callable($r_v[1])) {
					echo $r_v[1]( (object) $params, new Request() );
				} else {
					$controller_method = explode('@', $r_v[1]);

					### HANDLING ACTION
					try {
						if (count($controller_method) == 2){
							##### CHECK CONTROLLER FILE
							try {
								if (!file_exists($controller_dir . '/' . $controller_method[0] . '.php')) {
									throw new HandleException();						
								}
							} catch (HandleException $e){
								$e->renderError('File Controller "<b>'.$controller_method[0].'</b>" Tidak Ditemukan!', $controller_dir . '/<b>' . $controller_method[0] . '</b>.php', '<ul><li>Pastikan File Controller Sudah Dibuat dan Telah Diletakan Di Dalam Direktori Yang Benar! </li><li>Perhatikan Besar Kecil Huruf Dalam Nama Controller</li><li> Buat Controller Dengan Mudah Dengan Vifa Console : php vifa add:controller '.$controller_method[0] . '</li></ul>');
							}

							include_once $controller_dir . '/' . $controller_method[0] . '.php';

							#### CHECK CLASS CONTROLLER HAS LOADED
							try {
								if (!class_exists($controller_method[0])) {
									throw new HandleException();
								}
							} catch (HandleException $e){
								$e->renderError('File Controller "<b>'.$controller_method[0].'</b>" Tidak Diload!', $controller_dir . '/<b>' . $controller_method[0] . '</b>.php', '<ul><li>Pastikan File Controller Tidak Memiliki Namespace</li><li>Pastikan Struktur Class File Controller Sudah Benar</li><li>Pastikan Composer Autoload Harus Aktif Dan Meload Controllers</li><li> Buat Controller Dengan Mudah Dengan Vifa Console : php vifa add:controller '.$controller_method[0] . '</li></ul>');
							}

							$controller = new $controller_method[0]();

							#### CHECK METHOD EXIST IN CONTROLLER CLASS
							try {
								if (!method_exists($controller, $controller_method[1])) {
									throw new HandleException();
								}
							} catch (HandleException $e){
								$e->renderError('Metode <b>'.$controller_method[1]. '()</b> Tidak Ada Di Dalam Class <b>' . $controller_method[0].'</b>', $controller_dir . '/' . $controller_method[0] . '.php', '<ul><li>Pastikan Metode Tersebut Sudah Terdefinisi Di Dalam Controller</li><li>Pastikan Metode Telah Diset Ke Public</li></ul>');
							}

							#### LAUNCH METHOD
							echo $controller->{$controller_method[1]}( (object) $params, new Request() );



							#### LAUNCH

						} else {
							throw new HandleException();
						}
					} catch (HandleException $e) {
						$e->renderError('Aksi Route Tidak Di Tulis Dengan Benar!', 'system\routes\web.php', 'Pastikan Controller Dan Metode Dipisahkan Dengan Tanda addmail @.');
					}




				}

				return true;
				break;
			}
		}

		return false;
	}

	private static function clean_url($url, $return_array = false){
		$url = explode('/', $url);
		foreach ($url as $uri => $uv) {
			if ($uv == '') {
				unset($url[$uri]);
			}
		}
		if (!$return_array) {
			$url = implode('/', $url);
		}
		return $url;
	}

}