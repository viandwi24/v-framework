<?php

namespace vframework\request;

use vframework\lib\csrf;
use vframework\lib\session;
use vframework\lib\redirect;
use vframework\config\config;
use vframework\exception\handle as HandleException;

class HTTPRequests {
	private $input;

	public function __construct(){
	}
	public function __destruct(){
	}

	private function init_input(){
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$this->verify_csrf();
			$this->input = $_POST;
		} else {
			try {
				throw new HandleException();
			} catch (HandleException $e) {
				$e->renderError('Tidak dapat mengambil input request karena metode request bukan post!', get_called_class(), 'Input hanya dapat diambil melalui metode post.');
			}
		}
	}

	private function verify_csrf(){
		$csrf 			= new csrf(config::get('uniq_key'));

		if (!isset($_POST['_csrf'])) $_POST['_csrf'] = '';
		$csrf->verify($_POST['_csrf']);
		unset($_POST['_csrf']);	
	}

	public function input($key){			
			$this->init_input();

			if (null !== (@$this->input[$key])) {
				return $this->input[$key];
			} else {
				return '';
			}
	}

	public function all(){			
		$this->init_input();
		return $this->input;
	}

	private function str_replace_first($from, $to, $content) {
	    $from = '/'.preg_quote($from, '/').'/';
	    return preg_replace($from, $to, $content, 1);
	}


	public function getReqUrl($return_array = false){
		$reqUrl = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$reqUrl = explode('/', $reqUrl);
		foreach ($reqUrl as $rU => $rU_v) {
			if ($rU_v == '') {
				unset($reqUrl[$rU]);
			}
		}
		$reqUrl = PROTOCOL . '://' . implode('/', $reqUrl);
		$baseUrl = config::get('base_url');

		$reqUrl = str_replace($baseUrl, '', $reqUrl);
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

	public function validate($data){
		$validate = [];

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$validate[$key] = $value;
			}
		}

		####
		if (count($validate) > 0) {
			$this->validate_input($validate);
		}
	}

	private function validate_input($input) {
		$validate_error = [];
		$required_input = [];

		foreach ($input as $key => $value) {
			$rules = explode('|', $value);
			$input = str_replace(' ', '', $this->input($key));

			foreach ($rules as $rule) {
				$value = explode(':', $rule);
				if (count($value) == 2){
					$rule = $value[0];
					$value = $value[1];
				} else {
					$value = '';
				}

				switch ($rule) {
					case 'required':
						if ($input == '') {
							$required = false;
							array_push($validate_error, array('input' => $key, 'type' => 'Required', 'str' => "Required '$key'"));
						} else {
							array_push($required_input, $key);
						}
						break;
					
					case 'email':
						if (!preg_match("/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iu", $input)) {
							array_push($validate_error, array('input' => $key, 'type' => 'Invalid Email Address', 'str' => "Invalid Email Address in '$key'"));
						}
						break;
					case 'min':
						if (in_array($key, $required_input)) {
							if (strlen($input) < $value) array_push($validate_error, array('input' => $key, 'type' => 'Min $value Character', 'str' => "A minimum of $value characters are written in '$key'"));
						}
						break;
					case 'max':
						if (in_array($key, $required_input)) {
							if (strlen($input) > $value) array_push($validate_error, array('input' => $key, 'type' => 'Max $value Character', 'str' => "A Max of $value characters are written in '$key'"));
						}
						break;
					default:
						break;
				}
			}
		}

		if (count($validate_error) > 0) {
			
			$error 					= session::get('_error');
			$error['validation']	= $validate_error;

			session::set('_error', $error);
			redirect::now( session::get('_form') );
			die();
		}


	}
}