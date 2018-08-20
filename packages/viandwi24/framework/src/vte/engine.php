<?php

namespace vframework\vte;

class engine {
	private static $view_dir;
	private static $var_extends = '@extends';
	private static $var_content = '@content';
	private static $var_content_end = '@endcontent';
	private static $var_content_get	= '@get';
	private static $var_array;



	public static function parse($content, $view_dir, $data){
		self::$view_dir		= $view_dir;
		$file_content		= $content;

		self::$var_array 	= $data;

		$content 			= self::get_file($content);
		$file_extends		= self::parse_extends($content);
		$file_content 		= self::parse_content_all($content);

		if ($file_extends == '') {
			$file_extends = $content;
		}

		return self::render_all($file_extends, $file_content, $data);


	}

	private static function render_all($extends, $content, $variabel){
		#PARSE CONTENT
		$data = array();
		preg_match_all("/@get\('(.*?)'\)/s", $extends, $data);
		foreach ($data[1] as $key => $value) {
			if (isset($content[$value])) {
				$extends = str_replace($data[0][$key], $content[$value], $extends);
			} else {
				$extends = str_replace($data[0][$key], '', $extends);
			}
		}

		#PARSE DATA VARIABEL
		$data = array();
		preg_match_all("/{{(.*?)}}/s", $extends, $data);

		foreach ($data[1] as $key => $value) {
			$value = htmlspecialchars($value);
			$value = strip_tags($value);	
			$value = str_replace('	', '', $value);

			if (isset($variabel[$value])){
				$extends = str_replace($data[0][$key], $variabel[$value], $extends);
			} else {
				$extends = str_replace($data[0][$key], '', $extends);
			}
		}


		return $extends;
		//self::dd($data);
	}

	private static function parse_extends($str) {
		$data = array();
		
		preg_match_all("/@extends\('(.*?)'\)/s", $str, $data);
		//preg_match_all('/<div class=\"abc\">(.*?)<\/div>/s', $str, $data);

		//self::dd($data);
		
		$final_extends = '';
		foreach ($data[1] as $file) {
			$final_extends .= self::get_file($file);
		}

		return $final_extends;
	}

	private static function parse_content_all($content){
		$file_content		= self::parse_content($content);
		$file_content_new	= self::parse_content_new_line($content, $file_content);

		return $file_content_new;
	}

	private static function parse_content($str) {
		$data = array();
		
		preg_match_all("/@set\('(.*?)', '(.*?)'\)/s", $str, $data);

		$final_content = array();
		foreach ($data[1] as $key => $value) {
			$final_content[$value] = $data[2][$key];
		}

		//self::dd($final_content);
		return $final_content;
	}

	private static function parse_content_new_line($str, $old_content) {
		$data = array();
		
		preg_match_all("/@content\('(.*?)'\)(.*?)@endcontent/s", $str, $data);

		foreach ($data[1] as $key => $value) {
			$old_content[$value] = $data[2][$key];
		}

		return $old_content;
		//
	}

	private static function dd($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}

	private static function get_file($file_name) {
		$file_name = str_replace('.', '/', $file_name);
		$file = self::$view_dir . '/' . $file_name . '.php';

		if (!file_exists($file)) {
			echo "Error : File Not Found! [$file_name]";
			die();
		}

		//$output = file_get_contents($file);
		## PARSE VARIABEL
		if (is_array(self::$var_array)){
			foreach (self::$var_array as $d_k => $d_v) {
				${$d_k} = $d_v;
			}
		}

		ob_start();
		@require $file;
		$output = ob_get_clean();

		return $output;
	}
}