<?php

namespace vframework\vte;

use vframework\exception\handle as HandleException;
use vframework\lib\csrf;
use vframework\config\config;

class vte {
	private $view_dir;
	private $file_called;


	public function parse($file_called, $view_dir, $data_array){
		$this->view_dir		= $view_dir;
		$this->file_called 		= $file_called;




		#### GET FILE CALLED
		$file_called_name  	= str_replace('.', '/', $file_called);
		$file_called_name	= $this->view_dir . '/' . $file_called_name . '.php';
		$this->check_exist($file_called_name);


		## PARSE VARIABEL
		if (is_array($data_array)){
			foreach ($data_array as $d_k => $d_v) {
				${$d_k} = $d_v;
			}
		}

		ob_start();
		require $file_called_name;
		$file_called	 = ob_get_clean();



		#### GET CONTENT IN FILE CALLED
		preg_match_all("/@set\('(.*?)',(.*?)'(.*?)'\)/s", $file_called, $data);
		$content = array();
		foreach ($data[1] as $key => $value) {
			$content[$value] = $data[3][$key];
			$file_called 	 = str_replace($data[0][$key], '', $file_called);
		}
		
		preg_match_all("/@content\('(.*?)'\)(.*?)@endcontent/s", $file_called, $data);
		foreach ($data[1] as $key => $value) {
			$content[$value] = $data[2][$key];
			$file_called 	 = str_replace($data[0][$key], '', $file_called);
		}
		



		#### GET EXTENDS FILE
		preg_match_all("/@extends\('(.*?)'\)/s", $file_called, $data);
		$file_extends	 	= '';

		foreach ($data[1] as $key => $value) {
			$file_extends_name	= str_replace('.', '/', $value);
			$file_extends_name	= $this->view_dir . '/' . $file_extends_name . '.php';
			$this->check_exist($file_extends_name);

			ob_start();
			require $file_extends_name;
			$file_extends	 = ob_get_clean();


			#### PARSE "GET CONTENT"
			preg_match_all("/@get\('(.*?)'\)/s", $file_extends, $data_get);
			foreach ($data_get[1] as $get_key => $get_value) {
				if (isset($content[$get_value])) {
					$file_extends = str_replace($data_get[0][$get_key], $content[$get_value], $file_extends);
				} else {
					$file_extends = str_replace($data_get[0][$get_key], '', $file_extends);
				}
			}

			
			$file_called 	 = str_replace($data[0][$key], $file_extends, $file_called);	

		}



		#### PARSE "GET CONTENT" AGAIN
		preg_match_all("/@get\('(.*?)'\)/s", $file_called, $data_get);
		foreach ($data_get[1] as $get_key => $get_value) {
			if (isset($content[$get_value])) {
				$file_called = str_replace($data_get[0][$get_key], $content[$get_value], $file_called);
			} else {
				$file_called = str_replace($data_get[0][$get_key], '', $file_called);
			}
		}


		#### CSRF
		$csrf 			= new csrf(config::get('uniq_key'));
		$csrf_token 	= $csrf->get();
		preg_match_all("/<form(.*?)method=\"post\"(.*?)>(.*?)@csrf(.*?)/si", $file_called, $data);
		foreach ($data[0] as $key => $value) {
			$data[0][$key] = str_replace('@csrf', '<input name="_csrf" type="hidden" value="'.$csrf_token . '">', $data[0][$key]);

			$file_called = str_replace($value, $data[0][$key], $file_called);
		}

		##### UNSET AND CLEAR ALL VARIABLE
		unset($csrf);
		//unset($csrf_token);
		unset($data);
		unset($data_get);
		unset($get_key);
		unset($content);
		unset($file_extends);
		unset($file_extends_name);
		unset($file_called_name);
		unset($data_array);

		//$file_called 	= str_replace('@csrf', '<input name="_csrf" type="hidden" value="'.$csrf_token.'">', $file_called);



		return $file_called;
		





	}

	private function check_exist($file){
		if (!file_exists($file)) {
			try {
				throw new HandleException();				
			} catch (HandleException $e) {
				$e->renderError("<b>VIFA TEMPLATE ENGINE : </b> Error File Not Found [<b>$file</b>]", "system/app/view/<b>$this->file_called</b>.php", "Cek Apakah FIle VIew Ada dan Sudah Dibuat Pada Folder Yang Benar.");
			}
		}
	}


	private function dd($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}
}