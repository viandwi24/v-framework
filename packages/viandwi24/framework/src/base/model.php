<?php

namespace vframework\base;

use vframework\config\config;
use vframework\exception\handle as HandleException;
use PDO;
use PDOException;

abstract class model {
	######## MODEL VAR
	protected static $tb_name;
	protected static $id_collum = 'id';

	######## CONNECTION
	private static $db_driver;
	private static $db_host;
	private static $db_user;
	private static $db_password;
	private static $db_name;


	######## BASE MODEL VAR
	private static $pdo = NULL;
	private static $query_where = array();
	private static $query_orderby = NULL;
	private static $query_sort = 'ASC';

	######### TEMP VAR
	private static $temp_first = false;

	public static function init(){
		if (self::$pdo == NULL) {
			self::connect();
		}
	}

	protected static function connect(){
		self::$db_driver	= config::get('db_driver');
		self::$db_host		= config::get('db_host');
		self::$db_user 		= config::get('db_user');
		self::$db_password 	= config::get('db_password');
		self::$db_name 		= config::get('db_name');

		if(self::$db_host=='localhost'){
			// We have a little issue in unix systems when you set the host as localhost
			self::$db_host = '127.0.0.1';
		}

		#### USE CLASS PDO
		if (!isset(self::$pdo)){
			try {
				$dsn = self::$db_driver.':host='.self::$db_host.';dbname='.self::$db_name;
				self::$pdo = new PDO($dsn, self::$db_user, self::$db_password);
				self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			} catch(PDOException $e) {
				
				try {
					throw new HandleException();
				} catch(HandleException $he) {
					$he->renderError('<b>Model : </b>Gagal Melakukan Koneksi Ke Database | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , 'Cek Konfigurasi Database Apakah Sudah Benar Dan Pastikan Databse Telah Di Hidupkan Service dan Remote.');
				}
				
			}
		}
	}


	######################################################################################################
	public static function find($key){
		self::init();

		try {
			$final_query = "SELECT * FROM " . static::$tb_name . " WHERE " . static::$id_collum . "='" . $key . "'";
			$stmt = self::$pdo->prepare($final_query); 
	    	$stmt->execute();
	    	$result = $stmt->fetchALL(PDO::FETCH_OBJ);

	    	if (count($result) > 0) {
	    		return (object) $result[0];
	    	} else {
	    		return NULL;
	    	}
		} catch(PDOException $e) {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				$he->renderError('<b>Model : </b>Gagal Melakukan Find | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
			}
		}
	}

	private static function decWhere(){
		$query_where = '';
		if (count(self::$query_where) > 0) {
			$temp_query_where = '';

			$no = 0;
			foreach (self::$query_where as $qw_k => $qw_v) {
				if (count($qw_v) == 1) {
					### hasil : id='value'
					self::$query_where[$no] = static::$id_collum . "='" . $qw_v[0] . "'";
				} elseif (count($qw_v) == 2) {
					### hasil : collum='value'
					self::$query_where[$no] = $qw_v[0] . "='" . $qw_v[1] . "'";
				} elseif (count($qw_v) == 3) {
					self::$query_where[$no] = $qw_v[0] . $qw_v[1] ."'" . $qw_v[2] . "'";
				}
				$no++;
			}

			$query_where = "WHERE " . implode(self::$query_where, " AND ");
		}
		
		self::$query_where = array();
		//die($query_where);
		return $query_where;
	}

	private static function decOrderBy(){
		if (self::$query_orderby == NULL or self::$query_orderby == '') {
			return "ORDER BY " . static::$id_collum;
		} else {
			return "ORDER BY " . self::$query_orderby;
		}
	}
	public static function orderBy($coll, $sort) {
		self::$query_orderby = $coll;

		$sort = strtoupper($sort);
		if ($sort == 'DESC' or $sort == 'ASC') {
			self::$query_sort = $sort;
		} else {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				$he->renderError('<b>Model : </b>Gagal Melakukan orderBy | <b>PDO Error : </b>', 'Model Dipanggil : ' . get_called_class() , 'Sort - Parameter Kedua Hanya Disi Oleh ASC dan DESC.');
			}
		}

		return new static;
	}
	public static function where() {
		$arg = func_get_args();
		
		if (count($arg) == 2) {
			self::$query_where[count(self::$query_where)] = array($arg[0], $arg[1]);
			return new static;
		} elseif (count($arg) == 3) {

			self::$query_where[count(self::$query_where)] = array($arg[0], $arg[1], $arg[2]);
			return new static;				
		} else {
			die("ERROR : WHERE Mempunyai 2 Parameter atau 3 Parameter!!!!");
		}
	}
	public static function first(){
		self::$temp_first = true;
		return new static;
	}
	public static function get(){
		###### INIT PDO CLASS
		self::init();

		###### DEC - WHERE
		$query_where = self::decWhere();
		###### DEC - ORDER BY
		$query_orderby = self::decOrderBy();
		###### DEC - SORT
		$query_sort = self::$query_sort;


		###### BUILD QUERY
		$final_query =  "SELECT * FROM " . static::$tb_name .
						" " . $query_where . " " . $query_orderby . " ".$query_sort;
		


		try {
			###### LAUNCH QUERY						
			$stmt = self::$pdo->prepare($final_query); 
	    	$stmt->execute();
	    	$result = $stmt->fetchALL(PDO::FETCH_OBJ);
		} catch(PDOException $e) {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
						if (config::get('db_error_return_string')) {
							return $e->getMessage();
						} else {
							$he->renderError('<b>Model : </b>Gagal Melakukan Get | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
						}
			}
		}

    	if (count($result) > 0 and self::$temp_first) {
    		return $result[0];
			self::$temp_first = false;
    	} else {
    		return $result;
    	}

	}
	public static function insert($data){
		###### INIT PDO CLASS
		self::init();

		$final_query = '';
		#################################
		if (is_array(@$data[0])) {

			$no_par = 0;
			foreach ($data as $da_k => $da_v) {

				$insert_data_key = array();
				$insert_data_value = array();

				$no = 0;
				foreach ($da_v as $d_k => $d_v) {
					$insert_data_key[$no] = $d_k;	
					$insert_data_value[$no] = "'" . $d_v . "'";
					$no++;
				}

				$insert_data_key = '(' . implode(', ', $insert_data_key) . ')';
				$insert_data_value = '(' . implode(', ', $insert_data_value) . ')';

				###### BUILD QUERY
				$final_query = "INSERT INTO ".static::$tb_name." " . $insert_data_key . " VALUES " . $insert_data_value . ";";


		    	try {
					###### LAUNCH QUERY						
					$stmt = self::$pdo->prepare($final_query); 
			    	$output_query = $stmt->execute();
				} catch(PDOException $e) {
					try {
						throw new HandleException();
					} catch(HandleException $he) {
						if (config::get('db_error_return_string')) {
							return $e->getMessage();
						} else {
							$he->renderError('<b>Model : </b>Gagal Melakukan Insert | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
						}
					}
				}


				$no_par++;


				if (!$output_query === TRUE) {
				    return self::$pdo->errorInfo();
				    break;
				}
				
			} 

			return true;

		} else {


			$insert_data_key = array();
			$insert_data_value = array();

			$no = 0;
			foreach ($data as $d_k => $d_v) {
				$insert_data_key[$no] = $d_k;	
				$insert_data_value[$no] = "'" . $d_v . "'";
				$no++;
			}

			$insert_data_key = '(' . implode(', ', $insert_data_key) . ')';
			$insert_data_value = '(' . implode(', ', $insert_data_value) . ')';


			###### BUILD QUERY
			$final_query = "INSERT INTO " . static::$tb_name . " ". $insert_data_key ."	
							VALUES " . $insert_data_value . ";";


		    try {
				###### LAUNCH QUERY						
				$stmt = self::$pdo->prepare($final_query); 
			    $output_query = $stmt->execute();
			} catch(PDOException $e) {
				try {
					throw new HandleException();
				} catch(HandleException $he) {
					if (config::get('db_error_return_string')) {
						return $e->getMessage();
					} else {
						$he->renderError('<b>Model : </b>Gagal Melakukan Insert | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
					}
				}
			}

			if (!$output_query === TRUE) {
				return self::$pdo->errorInfo();
			} else {
				return true;
			}

		}
	}

	public static function delete(){
		###### INIT PDO CLASS
		self::init();

		###### DECOMPILE - WHERE
		$query_where = self::decWhere();
		
		$final_query =  "DELETE FROM ".static::$tb_name . " " . $query_where . ";";


		try {
			###### LAUNCH QUERY						
			$stmt = self::$pdo->prepare($final_query); 
			$output_query = $stmt->execute();
		} catch(PDOException $e) {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				if (config::get('db_error_return_string')) {
					return $e->getMessage();
				} else {
					$he->renderError('<b>Model : </b>Gagal Melakukan Delete | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
				}
			}
		}

		if (!$output_query === TRUE) {
			return self::$pdo->errorInfo();
		}

		return true;
	}

	public static function update() {

		$arg = func_get_args();
		$data = $arg[0];

		###### INIT PDO CLASS
		self::init();

		###### DECOMPILE - WHERE
		$query_where = self::decWhere();

		if (is_array($data) and count($arg) == 1) {
			$no = 0;
			foreach ($data as $d_k => $d_v) {
				$query_update[$no] = $d_k . "='" . $d_v . "'";
				$no++;
			}
			$query_update = implode(', ', $query_update);
		} elseif (count($arg) == 2) {
			$query_update = $arg[0] . "='" . $arg[1] . "'";
		}

		
		$final_query =  "UPDATE ".static::$tb_name." SET ".$query_update . " " . $query_where . ";";



		try {
			###### LAUNCH QUERY						
			$stmt = self::$pdo->prepare($final_query); 
			$output_query = $stmt->execute();
		} catch(PDOException $e) {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				if (config::get('db_error_return_string')) {
					return $e->getMessage();
				} else {
					$he->renderError('<b>Model : </b>Gagal Melakukan Update | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
				}
			}
		}


		if (!$output_query === TRUE) {
			return self::$pdo->errorInfo();
		}

		return true;
	}
	public static function query($query){
		###### INIT PDO CLASS
		self::init();
		
		$final_query =  $query;


		try {
			###### LAUNCH QUERY						
			$stmt = self::$pdo->prepare($final_query); 
			$output_query = $stmt->execute();
		} catch(PDOException $e) {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				if (config::get('db_error_return_string')) {
					return $e->getMessage();
				} else {
					$he->renderError('<b>Model : </b>Gagal Menjalankan Query | <b>PDO Error : </b>' . $e->getMessage(), 'Model Dipanggil : ' . get_called_class() , '<ul><li>Cek Tata Cara Penulisan Kode Dalam Model.</li><li>Cek Apakah Nama Tabel Sudah Benar</li><li>Cek Apakah Nama Kolom Sudah Benar</li></ul>');
				}
			}
		}

		if (!$output_query === TRUE) {
			return self::$pdo->errorInfo();
		}

		return true;
	}
}