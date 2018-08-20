<?php

namespace vframework\lib;

use vframework\exception\handle as HandleException;

class session {
	public static function set(){
		$arg = func_get_args();

		if (is_array($arg[0]) and count($arg) == 1) {
			foreach ($arg[0] as $key => $value) {
				$_SESSION[$key] = $value;
			}
		} elseif (count($arg) == 2) {
			$_SESSION[$arg[0]] = $arg[1];
		} else {
			try {
				throw new HandleException();				
			} catch (HandleException $e) {
				$e->renderError('Session Set Memilik  2 Parameter String Untuk Singgle Set dan 1 Parameter Type Array Untuk Multi Set.', get_called_class(), 'Lakukan Set Session Dengan Benar! Lihat Dokumentasi Lebih Lanjut,');
			}

		}
	}
	public static function get($key = NULL){

		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} elseif ($key == NULL) {
			return $_SESSION;
		} else {
			return NULL;
		}
	}
	public static function delete($key = NULL){

		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
			return true;
		} else {
			return false;
		}
	}
	public static function destroy(){
		session_destroy();
		$_SESSION = array();
		return true;
	}
}