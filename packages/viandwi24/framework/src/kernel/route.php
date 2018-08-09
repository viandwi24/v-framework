<?php

namespace vframework\kernel;

use vframework\kernel\config;
use vframework\kernel\router;
use vframework\kernel\error;

class route {
static $route_get = array();
static $route_post = array();
static $temp_route;

static $route_error = array();

	public function get($reqUrl, $response){
		self::$route_get[count(self::$route_get)] = array(self::clean_url($reqUrl), $response, array(), array());
		self::$temp_route = 'get';
		return new static;
	}
	public function post($reqUrl, $response){
		self::$route_post[count(self::$route_post)] = array(self::clean_url($reqUrl), $response, array(), array());
		self::$temp_route = 'post';
		return new static;
	}

	protected function clean_url($url){
		$url = explode('/', $url);
		foreach ($url as $uri => $uv) {
			if ($uv == '') {
				unset($url[$uri]);
			}
		}
		$url = implode('/', $url);
		return $url;
	}

	public function afterMiddleware(){
		for ($i=0; $i < func_num_args(); $i++) { 
			$new_mid[$i] = func_get_arg($i);
		}

		if (self::$temp_route == 'get') {
			self::$route_get[count(self::$route_get)-1][3] = $new_mid;
		} else {
			self::$route_post[count(self::$route_post)-1][3] = $new_mid;
		}

		return new static;
	}
	public function beforeMiddleware(){
		for ($i=0; $i < func_num_args(); $i++) { 
			$new_mid[$i] = func_get_arg($i);
		}
		if (self::$temp_route == 'get') {
			self::$route_get[count(self::$route_get)-1][2] = $new_mid;
		} else {
			self::$route_post[count(self::$route_post)-1][2] = $new_mid;
		}
		return new static;
	}

	public function show(){
		return array("POST" => self::$route_post, "GET" => self::$route_get);
	}

	public function now(){
		$route_post_now = self::process(self::$route_post, 'post');
		$route_get_now = self::process(self::$route_get, 'get');

		if (!$route_post_now) {
			if (!$route_get_now) {
				error::make('404');
			}
		}

	}

	protected function process($route, $method){
		$reqUrl = router::GetReqUrl(true);

		$success = false;
		$params = array();
		foreach ($route as $rp => $rp_v) {
			$routeReqUrl = explode('/', $rp_v[0]);
			$sama = true;


			## HANDLE URL YANG MEMILIKI KARAKTER KHUSUS / ANEH
			$block_word = array("?", "{", "}", "#");

			foreach ($block_word as $bw_k => $bw_v) {
				if (strpos(implode('/', $reqUrl), $bw_v) !== false) {
					error::make('system_error', "URL Berisi Character Yang Tidak Di Perbolehkan", implode('    ', $block_word));
					die();
				}
			}


			## MENDETEKSI ADANYA PARAMATER OPSIONAL KAH DI URL
			$param_opsional = false;
			foreach ($routeReqUrl as $urp => $urp_v) {
				$urp_param = explode('{', $urp_v);
				$urp_param_opsional = explode('?', $urp_v);
				
				if (count($urp_param_opsional) == 2 && $urp_param_opsional[1] == '}') {
					$param_opsional = true;
				}
			}

			## MENCARI CONTROLLER DAN METHOD
			if ($method == 'post') {
				if($_SERVER['REQUEST_METHOD'] == 'POST'){}else{$sama = false;}
			}

			if (count($routeReqUrl) == count($reqUrl) or $param_opsional == true) {
				foreach ($routeReqUrl as $urp => $urp_v) {
					if ($urp_v == @$reqUrl[$urp]) {
					} else {
						$urp_param = explode('{', $urp_v);
						$urp_param_opsional = explode('?', $urp_v);

						if (count($urp_param_opsional) == 2 && $urp_param_opsional[1] == '}') {
							$urp_param_opsional = explode('{', $urp_param_opsional[0]);
							
							$params[$urp_param_opsional[1]] = @$reqUrl[$urp];
						} elseif (count($urp_param) == 2) {
							$urp_param = explode('}', $urp_param[1]);
							$params[$urp_param[0]] = @$reqUrl[$urp];
						} else {
							$sama = false;
						}
					}
				}
			} else {
				$sama = false;
			}

			if ($sama) {

				$success = true;
				$middleware_dir = config::$app_dir . '/middleware';
				$beforeMiddleware = $rp_v[2];
				$afterMiddleware = $rp_v[3];

				### LOAD before MIDDLEWARE
				foreach ($beforeMiddleware as $mid_k => $mid_v) {
					require $middleware_dir . '/' . $mid_v . '.php';
					${$mid_v} = new $mid_v();
					${$mid_v}->handle();
				}

				### LOAD CONTROLLER or LAUNCH FUNCTION
				if (is_callable($rp_v[1])) {
					echo $rp_v[1]((object) $params , new Request());
				} else {
					$controller_dir = config::$app_dir . '/controllers';
					$rp_v_a = explode('@', $rp_v[1]);
					$controller_name = $rp_v_a[0];
					$method_name = $rp_v_a[1];

					require $controller_dir . '/'.$controller_name.'.php';
					$controller = new $controller_name();
					echo $controller->{$method_name}((object) $params , new Request());
				}

				### LOAD after MIDDLEWARE
				foreach ($afterMiddleware as $mid_k => $mid_v) {
					require $middleware_dir . '/' . $mid_v . '.php';
					${$mid_v} = new $mid_v();
					${$mid_v}->handle();
				}


				return $success;
				break;
			}
		}
			return $success;
	}


	#####################################################################################
	public function error($error_name, $error_function) {
		self::$route_error[count(self::$route_error)] = array($error_name, $error_function);
		return new static;
	}
	public function getError($error_name, $args) {
		if ($error_name != '') {
			foreach (self::$route_error as $re_k => $re_v) {
				if ($re_v[0] == $error_name) {
					if (is_callable($re_v[1])) {
						echo $re_v[1]($args);
					} else {
						echo "Error : route::error('name', function(){}), Parameter 2 Harus Berbentuk Fungsi!";
					}
					break;
				}
			}
		} else {
			echo "Error : route::getError($error_name, $args), $error_name tidak memiliki value!";
		}
		die();
	}

}


