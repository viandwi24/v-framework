<?php

namespace vframework\base;

use vframework\config\config;
use vframework\exception\handle as HandleException;
use PDO;
use PDOException;

class model {
	######## MODEL VAR
	protected static $tb_name;
	protected static $id_collum = 'id';

	######## CONNECTION
	private static $db_driver;
	private static $db_host;
	private static $db_user;
	private static $db_password;
	private static $db_name;

	####### MODEL VAR
	private static $pdo = null;

	####### QUERY
	private static $where_arr	= array();
	private static $order_by	= '';
	private static $sort		= 'ASC';
	
	######### TEMP VAR
	private static $temp_first = false;

	private static function init(){
		self::$db_driver	= config::get('db_driver');
		self::$db_host		= config::get('db_host');
		self::$db_user 		= config::get('db_user');
		self::$db_password 	= config::get('db_password');
		self::$db_name 		= config::get('db_name');

		if (self::$db_host == 'localhost') {
			self::$db_host = '127.0.0.1';
		}

		if (self::$pdo == null) {
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

	public static function first(){
		self::$temp_first = true;
		return new static;
	}

	private static function protect_data($data){
		return $data;
	}

	public static function where() {
		$arg = func_get_args();

		if (count($arg) == 2) {
			array_push(self::$where_arr, array(self::protect_data($arg[0]), self::protect_data($arg[1])));
		} elseif (count($arg) == 3) {
			array_push(self::$where_arr, array(self::protect_data($arg[0]), self::protect_data($arg[1]), self::protect_data($arg[2])));
		} else {

		}

		return new static;
	}

	public static function get(){
		self::init();

		$final_query 	= '';
		$bind_param 	= [];

		##### DECODE WHERE QUERY
		$query_where	= [];
		$where 			= self::$where_arr;
		$final_query_where = '';

		foreach ($where as $key => $value) {
			if (count($value) == 2) {
				array_push($query_where, "$value[0]=:where_$value[0]");
				array_push($bind_param, [':where_'.$value[0] ,$value[1]]);
			} elseif (count($value) == 3) {
				array_push($query_where, "$value[0]$value[1]:where_$value[0]");
				array_push($bind_param, [':where_'.$value[0] ,$value[2]]);
			}
		}
		if (count($query_where) > 0) {
			$final_query_where = implode(' AND ', $query_where);
			$final_query_where = "WHERE " . $final_query_where;
		} else {
			$final_query_where = '';
		}

		##### DECODE QUERY ORDER
		$final_query_order = self::$order_by;
		if ($final_query_order == '') $final_query_order = static::$id_collum;
		$final_query_order = "ORDER BY " . $final_query_order;

		##### DECODE QUERY SORT
		$final_query_sort = self::$sort;


		##### DECODE FINAL QUERY
		$final_query = "SELECT * FROM " . static::$tb_name . " " . $final_query_where . " " .
						$final_query_order . " " . $final_query_sort;
		


		



		try {
			##### RUN QUERY
			#die($final_query);
			$stmt = self::$pdo->prepare($final_query); 

			# BINDPARAM
			foreach ($bind_param as $key => $value) {
				$stmt->bindParam($value[0], $value[1]);
			}

			# EXECUTE QUERY
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

	    # RETURN RESULT
		if (count($result) > 0 and self::$temp_first) {
    		return $result[0];
			self::$temp_first = false;
    	} else {
    		return $result;
    	}
	}
	public static function orderBy($coll, $sort) {
		self::$order_by = $coll;

		$sort = strtoupper($sort);
		if ($sort == 'DESC' or $sort == 'ASC') {
			self::$sort 	= $sort;
		} else {
			try {
				throw new HandleException();
			} catch(HandleException $he) {
				$he->renderError('<b>Model : </b>Gagal Melakukan orderBy | <b>PDO Error : </b>', 'Model Dipanggil : ' . get_called_class() , 'Sort - Parameter Kedua Hanya Disi Oleh ASC dan DESC.');
			}
		}

		return new static;
	}
	public static function find($key){
		self::init();

		$key = self::protect_data($key);

		try {
			$final_query = "SELECT * FROM " . static::$tb_name . " WHERE " . static::$id_collum . "=:key";
			$stmt = self::$pdo->prepare($final_query); 
			$stmt->bindParam(':key', $key);
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

	public static function insert($data) {
		self::init();

		$final_query = '';
		if (is_array(@$data[0])) {

			foreach ($data as $datanya) {
				$bind_param = [];
				$insert_data_key = array();
				$insert_data_value = array();

				foreach ($datanya as $key => $value) {
					array_push($insert_data_key, $key);
					array_push($insert_data_value, ":".$key);
					array_push($bind_param, [":".$key, $value]);
				}


				$insert_data_key = '(' . implode(', ', $insert_data_key) . ')';
				$insert_data_value = '(' . implode(', ', $insert_data_value) . ')';

				###### BUILD QUERY
				$final_query = "INSERT INTO " . static::$tb_name . " ". $insert_data_key ."	
								VALUES " . $insert_data_value . ";";

				try {
					###### LAUNCH QUERY						
					$stmt = self::$pdo->prepare($final_query); 
					foreach ($bind_param as $key => $value) {
						$stmt->bindParam($value[0], $value[1]);
					}
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
				}
			}
			return true;

		} elseif (is_array($data)) {
			$bind_param = [];
			$insert_data_key = array();
			$insert_data_value = array();

			foreach ($data as $key => $value) {
				array_push($insert_data_key, $key);
				array_push($insert_data_value, ":".$key);
				array_push($bind_param, [":".$key, $value]);
			}


			$insert_data_key = '(' . implode(', ', $insert_data_key) . ')';
			$insert_data_value = '(' . implode(', ', $insert_data_value) . ')';

			###### BUILD QUERY
			$final_query = "INSERT INTO " . static::$tb_name . " ". $insert_data_key ."	
							VALUES " . $insert_data_value . ";";

			try {
				###### LAUNCH QUERY						
				$stmt = self::$pdo->prepare($final_query); 
				foreach ($bind_param as $key => $value) {
					$stmt->bindParam($value[0], $value[1]);
				}
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
		self::init();

		$final_query 	= '';
		$bind_param 	= [];

		##### DECODE WHERE QUERY
		$query_where	= [];
		$where 			= self::$where_arr;
		$final_query_where = '';

		foreach ($where as $key => $value) {
			if (count($value) == 2) {
				array_push($query_where, "$value[0]=:where_$value[0]");
				array_push($bind_param, [':where_'.$value[0] ,$value[1]]);
			} elseif (count($value) == 3) {
				array_push($query_where, "$value[0]$value[1]:where_$value[0]");
				array_push($bind_param, [':where_'.$value[0] ,$value[2]]);
			}
		}
		if (count($query_where) > 0) {
			$final_query_where = implode(' AND ', $query_where);
			$final_query_where = "WHERE " . $final_query_where;
		} else {
			$final_query_where = '';
		}


		
		$final_query =  "DELETE FROM ".static::$tb_name . " " . $final_query_where . ";";


		try {
			###### LAUNCH QUERY						
			$stmt = self::$pdo->prepare($final_query); 
			
			# BINDPARAM
			foreach ($bind_param as $key => $value) {
				$stmt->bindParam($value[0], $value[1]);
			}

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

		self::init();

		###### DECOMPILE - WHERE
		$final_query 	= '';
		$bind_param 	= [];

		##### DECODE WHERE QUERY
		$query_where	= [];
		$where 			= self::$where_arr;
		$final_query_where = '';

		foreach ($where as $key => $value) {
			if (count($value) == 2) {
				array_push($query_where, "$value[0]=:where_$value[0]");
				array_push($bind_param, [':where_'.$value[0] ,$value[1]]);
			} elseif (count($value) == 3) {
				array_push($query_where, "$value[0]$value[1]:where_$value[0]");
				array_push($bind_param, [':where_'.$value[0] ,$value[2]]);
			}
		}
		if (count($query_where) > 0) {
			$final_query_where = implode(' AND ', $query_where);
			$final_query_where = "WHERE " . $final_query_where;
		} else {
			$final_query_where = '';
		}


		if (is_array($data) and count($arg) == 1) {
			$no = 0;
			foreach ($data as $d_k => $d_v) {
				$query_update[$no] = $d_k . "=:update_" . $d_k;
				array_push($bind_param, [':update_'.$d_k , $d_v]);
				$no++;
			}
			$query_update = implode(', ', $query_update);
		} elseif (count($arg) == 2) {
			$query_update = $arg[0] . "=:" . $arg[0];
			array_push($bind_param, [':update_'.$arg[0] , $arg[1]]);
		}

		
		$final_query =  "UPDATE ".static::$tb_name." SET ".$query_update . " " . $final_query_where . ";";



		try {
			###### LAUNCH QUERY						
			$stmt = self::$pdo->prepare($final_query); 
			
			# BINDPARAM
			foreach ($bind_param as $key => $value) {
				$stmt->bindParam($value[0], $value[1]);
			}

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
}