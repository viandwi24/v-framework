<?php

namespace vifaframework\view;

use vifaframework\config\config;
use vifaframework\view\render;
use vifaframework\exception\handle as HandleException;

abstract class view {
	public static function make($filename, $_data = ''){
		$view_dir 	= SYSTEM_ROOT . '/app/views';
		$filename 	= str_replace('.', '/', $filename);
		$vte 		= new render($view_dir);

		if (is_array($_data)){
			foreach ($_data as $d_k => $d_v) {
				${$d_k} = $d_v;
			}
		}

		try {
			if (!file_exists($view_dir . '/' . $filename . '.php')) {
				throw new HandleException('File View <b>' . $filename . '</b> Tidak Ada!',  $view_dir . '/' . $filename . '.php', 'Pastikan Anda Sudah Membuat File View Tersebut, Dan Pastikan Juga File View Dalam Dir Yang Tepat.');
			}
		} catch (HandleException $e) {
			$e->renderError();
		}



		## RENDER VIEW
		ob_start();
		echo $vte->make($filename, $_data);
		$output = ob_get_clean();

		return $output;

	}	
}