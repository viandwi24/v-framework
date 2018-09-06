<?php

namespace vifaframework\http;

use vifaframework\router\api as route_api;
use vifaframework\config\config;
use vifaframework\lib\csrf;
use vifaframework\lib\session;
use vifaframework\lib\redirect;
use vifaframework\exception\handle as HandleException;

class Request {
	private $input;
	private $file;
	private $file_selected;
	private $log_upload_info = null;
	private $log_upload_error = null;
	private $verify_csrf = false;

	/**
	 * GET REQUEST URL
	 *
	 * @param 	(boolean $return_array)
	 * @return 	str or array => $return_array
	 *
	 */
	public function getRequestUri($return_array = false)
	{
		$base_url  	 	= config::get('base_url');
		$full_requrest 	= PROTOCOL . '://' .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$request_url  	= str_replace($base_url, '', $full_requrest);

		## CLEANING URL
		$request_url = explode('/', $request_url);
		foreach ($request_url as $key => $value) {
			if ($value == ''){
				unset($request_url[$key]);
			}
		}
		$request_url = implode('/', $request_url);

		$get = explode('?', $request_url);
		if (count($get) >= 2) {


			if (!count($_GET) > 0 ) {
				$nowurl = str_replace('?', '<b><u>?</u></b>', $full_requrest);
				try {
					throw new HandleException('URL Request Berisi Karakter Yang Tidak Diperbolehkan. ['.$nowurl.']', get_called_class(), 'Karakter Tidak Diperbolehkan : ?  {   }');
				} catch (HandleException $e) {
					$e->renderError();
				}
			}

			$new_get = [];
			foreach ($_GET as $key => $value) {
				array_push($new_get, "$key=$value");
			}
			$new_get = '?'.implode('&', $new_get);
			$request_url = str_replace($new_get, '', $request_url);
		}

		$request_url = explode('/', $request_url);

		if ($return_array)
		{
			return $request_url;
		} else 
		{
			return implode('/', $request_url);
		}
	}


	private function init_input(){
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			if (route_api::$route_api == true) {
				if (route_api::$csrf_token == true) {
					$this->verify_csrf();
				}
			} else {
				$this->verify_csrf();
			}

			$this->input = $_POST;
			$this->file = $_FILES;
		} else {
			try {
				throw new HandleException('Tidak dapat mengambil input request karena metode request bukan post!', get_called_class(), 'Input hanya dapat diambil melalui metode post.');
			} catch (HandleException $e) {
				$e->renderError();
			}
		}
	}

	private function verify_csrf(){
		if ($this->verify_csrf == false) {
			$csrf 			= new csrf(config::get('uniq_key'));

			if (!isset($_POST['_csrf'])) $_POST['_csrf'] = '';
			
			if (!$csrf->verify($_POST['_csrf'])) error::make('csrf_error', get_called_class());

			unset($_POST['_csrf']);	

			$this->verify_csrf = true;
		}
	}

	public function input($key){			
			$this->init_input();

			if (null !== (@$this->input[$key])) {
				return $this->input[$key];
			} else {
				return '';
			}
	}

	public function file($key){			
			$this->init_input();

			if (null !== (@$this->file[$key])) {
				$this->file_selected = $this->file[$key];
				
				$path = $this->file_selected['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);

				$this->file_selected['ext'] 	 	= $ext;
				$this->file_selected['random_name'] = $this->random_str(32) . '.' . $ext;
			} else {
				$this->file_selected = null;				
			}
			
			return $this;
	}

	public function detail(){	
		$this->init_input();

		if ($this->file_selected == null) {
			return null;
		} else {
			return $this->file_selected;
		}
	}

	private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	    $pieces = [];
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $pieces []= $keyspace[random_int(0, $max)];
	    }
	    return implode('', $pieces);
	}

	public function upload_info(){
		if ($this->log_upload_info == null) {
			return null;
		} else {
			$data = $this->log_upload_info;
			$this->log_upload_info = null;
			return $data;
		}
	}

	public function upload_error(){
		if ($this->log_upload_error == null) {
			return null;
		} else {
			$data = $this->log_upload_error;
			$this->log_upload_error = null;
			return $data;
		}
	}

	public function upload($config){
		$this->init_input();
		
		if ($this->file_selected == null) {			
			return "Error File Not Found";
		} else {
			$file 	 	 	= $this->file_selected;
			$temp_file  	= $file['tmp_name'];
			$file['ext']	= strtoupper($file['ext']);
			
			#### ACCPETED THE FILE
			if (isset($config['acceptable'])) {
				$acceptable = strtoupper($config['acceptable']);
				$acceptable = explode('|', $acceptable);

				if (!in_array($file['ext'], $acceptable)) {
					$this->log_upload_error[] = "Error File Blocked";
					return false;
				}
			}
			#### BLOCKED THE FILE
			if (isset($config['blocked'])) {
				$blocked = strtoupper($config['blocked']);
				$blocked = explode('|', $blocked);

				if (in_array($file['ext'], $blocked)) {
					$this->log_upload_error[] = "Error File Blocked";
					return false;
				}
			}


			### CONFIGURATION PATH
			if (isset($config['path'])) {
				$upload_dir  	= ROOT . '/' . $config['path'];
			} else {
				$upload_dir  	= ROOT;
			} 

			### CONFIGURATION NAME
			if (isset($config['naming'])) {
				$config['naming'] = strtoupper($config['naming']);
				switch ($config['naming']) {
					case 'RANDOM':
						$upload_name = $file['random_name'];
						break;
					case 'FIXED':
						$upload_name = $file['name'];
						break;					
					case 'CUSTOM':
						if (isset($config['name'])) {
							$upload_name = $config['name'];
						} else {
							$this->log_upload_error[] = "Error Name Not Configured";
							return false;
						}
						break;					
					default:
						$upload_name = $file['random_name'];
						break;
				}
			} else {
				$upload_name = $file['random_name'];
			}

			###CHECK SIZE LIMIT
			if (isset($config['min_size'])) {
				if ($file['size'] < $config['min_size']) {
					$this->log_upload_error[] = "Error File Too Small";
					return false;
				}
			}
			if (isset($config['max_size'])) {
				if ($file['size'] > $config['max_size']) {
					$this->log_upload_error[] = "Error File Too Large";
					return false;
				}
			}


			### CHECK FILE EXIST BEFORE UPLOAD
			if (file_exists($upload_dir . '/' . $upload_name)) {
				$this->log_upload_error[] = "Error File Exists";
				return false;
			}

			if (move_uploaded_file($temp_file, $upload_dir . '/' . $upload_name)) {
				$this->log_upload_info = [
											'path' 	=> $upload_dir,
											'ext' 	=> $file['ext'],
											'name' 	=> $upload_name,
											'size' 	=> $file['size'],
										 ];
				return true;
			} else {
				$this->log_upload_error[] = "Error Move File";
				return false;
			}

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


	public function validate($data){
		$this->init_input();

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
			$value = strtolower($value);
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
					
					case 'int':
						if (!is_int($in_input)) {
							array_push($validate_error, array('input' => $key, 'type' => 'Must Be Int', 'str' => "'$key' Must Be Integer"));
						}
						break;

					case 'str':
						if (!is_string($input)) {
							array_push($validate_error, array('input' => $key, 'type' => 'Must Be Str', 'str' => "'$key' Must Be String"));
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
			
			$app 							= session::get('__app__');
			$app['_error']['validation']	= $validate_error;

			session::set('__app__', $app);
			redirect::now( session::get('__app__')['last_form'] );
			die();
		}


	}
}