<?php

namespace vifaframework\router;

use vifaframework\exception\handle as HandleException;
use vifaframework\config\config;

class error {
	private static $error_route  = array();

	private static function init(){

	}


	public static function make(){
		$name = func_get_arg(0);
		$arg = func_get_args();
		unset($arg[0]);
		$arg = implode('[][][][][]', $arg);
		$arg = explode('[][][][][]', $arg);

		self::route($name, $arg);
	}
	
	public static function route($name, $arg){

		$routes_dir = SYSTEM_ROOT . '/routes';

		try {
			if (!file_exists($routes_dir . '/error.php')) {
				throw new HandleException('Tidak Dapat Menemukan File Routes <b>error</b>!', 'system/routes/<b>error.php</b>', 'Perbaiki File Routes Yang Hilang Menggunakan Vifa Console : php vifa reset:routes <b>error</b>');	
			}


			require $routes_dir . '/error.php';
			self::now($name, $arg);

		} catch (HandleException $e){
			$e->renderError();
		}
	}

	public static function now($name, $arg){
		if (isset(self::$error_route[$name])) {
			$action = self::$error_route[$name];
			echo $action($arg);
			die();
		} else {

			try {
				throw new HandleException('Route Error Yang Di Panggil Tidak Ada. Nama Route Erro Yang Dipanggil : <b>'.$name.'</b>', get_called_class(), '<ul><li>Pastikan Nama Route Error Yang Dipanggil Sudah Benar</li><li>Pastikan Route Error Dengan Nama Ini Sudah Dibuat</li></ul>');
			} catch (HandleException $e){
				$e->renderError();
			}
		}
	}


	public static function set($name,$action){
		if (is_callable($action)) {
			self::$error_route[$name] = $action;
		} else {
			try {
				throw new HandleException('Parameter Kedua atau Aksi Error Route Harus Berupa Fungsi.', 'system\routes\error.php' , 'Pastikan Berbentuk Fungsi Yang Dapat Dipanggil');
			} catch(HandleException $he) {
				$he->renderError();
			}
		}
	}
}