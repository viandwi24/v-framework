<?php

namespace vframework\base;

use vframework\kernel\config;

class view {
	public function make($filename, $data = ''){
		$view_dir = config::$app_dir . '/views';
		$filename = str_replace('.', '/', $filename);

		if (is_array($data)){
			foreach ($data as $d_k => $d_v) {
				${$d_k} = $d_v;
			}
		}

		ob_start();
		require __DIR__ . '../../view/function.php';
		require $view_dir . '/' . $filename . '.php';
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}