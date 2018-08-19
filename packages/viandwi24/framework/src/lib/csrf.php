<?php

namespace vframework\lib;

use vframework\lib\session;
use vframework\exception\handle as HandleException;
use vframework\lib\date;
use vframework\lib\csrf;

class csrf {
	private $key;
	
	public function __construct($key){
		$key = str_replace(' ', '', $key);
		if ($key == '') {
			$this->key = 'vframework';
		}
	}

	public function get(){
		$token 		= password_hash(microtime(true) . "vframework" . date::get('dmYHis') . $this
			->key, PASSWORD_DEFAULT);
		$session 	= session::set('_csrf', $token);

		return $token;
	}

	public function verify($token){
		if (!session::get('_csrf') == $token) {
			try {
				throw new HandleException();				
			} catch (HandleException $e) {
				$e->renderError("Error CSRF Token!", get_called_class(), "Lakukan Refresh Kembali Pada Form. Kami Menggunakan CSRF Untuk Mengamankan Form.");
			}
		}

		session::set('_csrf', '');
	}
}