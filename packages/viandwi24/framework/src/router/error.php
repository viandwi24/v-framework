<?php

namespace vframework\router;

use vframework\exception\handle as HandleException;
use vframework\config\config;

class error {
	private static $error_route  = array();

	private static function init(){

	}
	
	public static function route($name, $arg){

		$routes_dir = config::get('routes_dir');

		try {
			$routes_file = ['web', 'console', 'api', 'error'];

			foreach ($routes_file as $file) {
				if (!file_exists($routes_dir . '/' . $file . '.php')) {
					throw new HandleException();	
					break;
				}
			}

			###### ROUTE WEB.PHP

			require $routes_dir . '/error.php';
			self::now($name, $arg);

		} catch (HandleException $e){
			$e->renderError('Tidak Dapat Menemukan File Routes <b>'.$file.'</b>!', 'system/routes/<b>'.$file.'.php</b>', 'Perbaiki File Routes Yang Hilang Menggunakan Vifa Console : php vifa reset:routes <b>'.$file.'</b>');
		}
	}

	public static function now($name, $arg){
		if (isset(self::$error_route[$name])) {
			$action = self::$error_route[$name];
			echo $action($arg);
			die();
		} else {

			try {
				throw new HandleException();
			} catch (HandleException $e){
				$e->renderError('Route Error Yang Di Panggil Tidak Ada. Nama Route Erro Yang Dipanggil : <b>'.$name.'</b>', get_called_class(), '<ul><li>Pastikan Nama Route Error Yang Dipanggil Sudah Benar</li><li>Pastikan Route Error Dengan Nama Ini Sudah Dibuat</li></ul>');
			}
		}
	}


	public static function set($name,$action){
		if (is_callable($action)) {
			self::$error_route[$name] = $action;
		} else {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				$he->renderError('Parameter Kedua atau Aksi Error Route Harus Berupa Fungsi.', 'system\routes\error.php' , 'Pastikan Berbentuk Fungsi Yang Dapat Dipanggil');
			}
		}
	}
}