<?php

namespace vframework\base;

use vframework\config\config;
use vframework\vte\render;
use vframework\exception\handle as HandleException;

abstract class view {
	public function make($filename, $data = ''){
		$view_dir 	= config::get('app_dir') . '/views';
		$filename 	= str_replace('.', '/', $filename);
		$vte 		= new render($view_dir);

		if (is_array($data)){
			foreach ($data as $d_k => $d_v) {
				${$d_k} = $d_v;
			}
		}

		try {
			if (!file_exists($view_dir . '/' . $filename . '.php')) {
				throw new HandleException();
			}
		} catch (HandleException $e) {
			$e->renderError('File View <b>' . $filename . '</b> Tidak Ada!',  $view_dir . '/' . $filename . '.php', 'Pastikan Anda Sudah Membuat File View Tersebut, Dan Pastikan Juga File View Dalam Dir Yang Tepat.');
		}



		## RENDER VIEW
		ob_start();
		require __DIR__ . '../view_function.php';
		echo $vte->make($filename, $data);
		$output = ob_get_clean();

		return $output;

	}	
}