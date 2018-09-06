<?php

namespace vifaframework\view;

use vifaframework\exception\handle as HandleException;
use vifaframework\lib\csrf;
use vifaframework\config\config;
use vifaframework\lib\session;

class vte {
	private $_view_dir;
	private $_file_called;


	public function parse($_file_called, $_view_dir, $_data_array, $_file_type = 'file', $_content = array()){
		$this->view_dir		= $_view_dir;

		if ($_file_type == 'file') {
			$this->file_called 		= $_file_called;
			#### GET FILE CALLED
			$_file_called_name  	= str_replace('.', '/', $_file_called);
			$_file_called_name	= $this->view_dir . '/' . $_file_called_name . '.php';
			$this->check_exist($_file_called_name);

		}


		## PARSE VARIABEL
		if (is_array($_data_array)){
			foreach ($_data_array as $_d_k => $_d_v) {
				${$_d_k} = $_d_v;
			}
		}
		
		$_app 					= session::get('__app__');
		$_error_sesi 			= @$_app['_error'];
		if ($_error_sesi == '' or $_error_sesi == null) $_error_sesi = [];
		if (!isset($_error_sesi['validation']))	$_error_sesi['validation'] = [];
		

		$error = $_error_sesi;

		unset($_app['_error']);
		session::set('__app__', $_app);
		unset($_error_sesi);
		unset($_app);
		

		if ($_file_type == 'file') {
			ob_start();
			require $_file_called_name;
			$_file_called	 = ob_get_clean();
		} else {
			$_file_called     = $_file_called;
		}



		#### GET CONTENT IN FILE CALLED
		preg_match_all("/@set\('(.*?)',(.*?)'(.*?)'\)/s", $_file_called, $_data);
		$_content = array();
		foreach ($_data[1] as $_key => $_value) {
			$_content[$_value] = $_data[3][$_key];
			$_file_called 	 = str_replace($_data[0][$_key], '', $_file_called);
		}
		
		preg_match_all("/@content\('(.*?)'\)(.*?)@endcontent/s", $_file_called, $_data);
		foreach ($_data[1] as $_key => $_value) {
			$_content[$_value] = $_data[2][$_key];
			$_file_called 	 = str_replace($_data[0][$_key], '', $_file_called);
		}
		



		#### GET EXTENDS FILE
		preg_match_all("/@extends\('(.*?)'\)/s", $_file_called, $_data);
		$_file_extends	 	= '';

		foreach ($_data[1] as $_key => $_value) {
			$_file_extends_name	= str_replace('.', '/', $_value);
			$_file_extends_name	= $this->view_dir . '/' . $_file_extends_name . '.php';
			$this->check_exist($_file_extends_name);

			ob_start();
			require $_file_extends_name;
			$_file_extends	 = ob_get_clean();



			#### PARSE "GET CONTENT"
			preg_match_all("/@get\('(.*?)'\)/s", $_file_extends, $_data_get);
			foreach ($_data_get[1] as $_get_key => $_get_value) {
				if (isset($_content[$_get_value])) {
					$_file_extends = str_replace($_data_get[0][$_get_key], $_content[$_get_value], $_file_extends);
				} else {
					$_file_extends = str_replace($_data_get[0][$_get_key], '', $_file_extends);
				}
			}

			
			$_file_called 	 = str_replace($_data[0][$_key], $_file_extends, $_file_called);	

		}

		preg_match_all("/@extends\('(.*?)'\)/s", $_file_called, $_data_coba);

		
		if (count($_data_coba[0])>0){
			return $this->parse($_file_called, $_view_dir, $_data_array, 'str', $_content);
		}



		#### PARSE "GET CONTENT" AGAIN
		preg_match_all("/@get\('(.*?)'\)/s", $_file_called, $_data_get);
		foreach ($_data_get[1] as $_get_key => $_get_value) {
			if (isset($_content[$_get_value])) {
				$_file_called = str_replace($_data_get[0][$_get_key], $_content[$_get_value], $_file_called);
			} else {
				$_file_called = str_replace($_data_get[0][$_get_key], '', $_file_called);
			}
		}


		#### CSRF
		preg_match_all("/<form(.*?)method=\"post\"(.*?)>(.*?)@csrf(.*?)/si", $_file_called, $_data);

		if (count($_data[0]) > 0) {
			$_csrf 			= new csrf(config::get('uniq_key'));
			$_csrf_token 	= $_csrf->get();

			foreach ($_data[0] as $_key => $_value) {
				$_data[0][$_key] = str_replace('@csrf', '<input name="_csrf" type="hidden" value="'.$_csrf_token . '">', $_data[0][$_key]);

				$_file_called = str_replace($_value, $_data[0][$_key], $_file_called);
			}
		}

		##### UNSET AND CLEAR ALL VARIABLE
		unset($_csrf);
		//unset($_csrf_token);
		unset($_data);
		unset($_data_get);
		unset($_get_key);
		unset($_content);
		unset($_file_extends);
		unset($_file_extends_name);
		unset($_file_called_name);
		unset($_data_array);

		//$_file_called 	= str_replace('@csrf', '<input name="_csrf" type="hidden" value="'.$_csrf_token.'">', $_file_called);

		$_file_called = str_replace('&addmail;', '@', $_file_called);

		return $_file_called;
		





	}

	private function check_exist($_file){
		if (!file_exists($_file)) {
			try {
				throw new HandleException("<b>VIFA TEMPLATE ENGINE : </b> Error File Not Found [<b>$_file</b>]", "system/app/view/<b>$this->file_called</b>.php", "Cek Apakah FIle VIew Ada dan Sudah Dibuat Pada Folder Yang Benar.");				
			} catch (HandleException $_e) {
				$_e->renderError();
			}
		}
	}

	public function parse_now($_file_called, $_view_dir, $_data_array){
		$this->view_dir		= $_view_dir;
		$this->file_called 		= $_file_called;

		$_file = $this->get_file($_file_called);

		$this->dd($_file);
	}

	private function get_file($_file_called){

		$_file_called_name	= $this->view_dir . '/' . $_file_called . '.php';
		$this->check_exist($_file_called_name);

		ob_start();
		require $_file_called_name;
		$_file_called	 = ob_get_clean();

		$this->get_extends($_file_called);
	}

	private function get_extends($_file) {
		preg_match_all("/@extends\('(.*?)'\)/s", $_file, $_data);

		$_no = 0;
		while (count($_data[0]) > 0) {
			$_data[1][$_no] = str_replace('.', '/', $_data[1][$_no]);
			$_file_called_name	= $this->view_dir . '/' . $_data[1][$_no] . '.php';
			
			echo "$_no ". $_file_called_name . "<br>";
			
			ob_start();
			require $_file_called_name;
			$_file	   .= ob_get_clean();

			$_file  	 	= str_replace($_data[0][$_no], '', $_file);
			unset($_data[0][$_no]);
			unset($_data[1][$_no]);

			preg_match_all("/@extends\('(.*?)'\)/s", $_file, $_data_dua);
			if (count($_data_dua[0]) > 0){
				foreach ($_data_dua[0] as $_key => $_value) {
					//$this->dd($_data);

					array_push($_data[0], $_value);
					array_push($_data[1], $_data_dua[1][$_key]);
				}
				$this->dd($_data);

			}


			$_no++;
		}

		echo $_file;
	}

	private function dd($_data){
		echo "<pre>";
		print_r($_data);
		echo "</pre>";
		die();
	}
}