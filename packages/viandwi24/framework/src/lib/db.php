<?php

namespace vframework\lib;

use vframework\kernel\error;
use vframework\kernel\config;
use mysqli;

class db {
	private static $instace;
	private static $mysqli;

	protected static $tb_name;
	protected static $query_where = array();
	protected static $temp_query;

	protected static $db_host;
	protected static $db_user;
	protected static $db_password;
	protected static $db_name;


	public function tb($tb_name){
		self::$tb_name = $tb_name;
		return new static;
	}

	public function where() {
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

	protected function connect(){
		#### 
		self::$db_host = config::get('db_host');
		self::$db_user  = config::get('db_user');
		self::$db_password = config::get('db_password');
		self::$db_name = config::get('db_name');

		@self::$mysqli = new mysqli(self::$db_host, self::$db_user, self::$db_password, self::$db_name);

		### CHECK KONEKSI
		if (self::$mysqli->connect_error) {
		    error::make("system_error", "DB Error",self::$mysqli->connect_error);
		}
	}

	protected function decWhere(){
		$query_where = '';
		if (count(self::$query_where) > 0) {
			$temp_query_where = '';

			$no = 0;
			foreach (self::$query_where as $qw_k => $qw_v) {
				if (count($qw_v) == 2) {
					### hasil : collum='value'
					self::$query_where[$no] = $qw_v[0] . "='" . $qw_v[1] . "'";
				} elseif (count($qw_v) == 3) {
					self::$query_where[$no] = $qw_v[0] . $qw_v[1] ."'" . $qw_v[2] . "'";
				}
				$no++;
			}

			$query_where = "WHERE " . implode(self::$query_where, " AND ");
		}

		return $query_where;
	}

	public function get(){
		############### Conecting To DB
		self::connect();

		############### DECOMPILE - WHERE
		#### Array Dari Where Akan Di Decompile Menjadi Bentuk Query
		$query_where = self::decWhere();

		### BUILD QUERY
		$final_query =  "SELECT * FROM " . self::$tb_name .
						" " . $query_where;

		### RUN QUERY
		$output_query = self::$mysqli->query($final_query);


		if (!$output_query === TRUE) {
		    error::make("system_error", "DB Error", self::$mysqli->error);
		} else {
			//return $output_query->fetch_all();

			$final_output_query = array();
			$no = 0;
			while ($row = $output_query->fetch_assoc()) {
				$final_output_query[$no] = (object) $row;
				$no++;
			}

			//return $final_query;
			return $final_output_query;
		}

		### CLOSE CONECTION
		self::$mysqli->close();
	}

	public function update() {

		$arg = func_get_args();
		$data = $arg[0];

		############### Conecting To DB
		self::connect();

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

		
		$final_query =  "UPDATE ".self::$tb_name." SET ".$query_update . " " . $query_where . ";";

		### RUN QUERY
		$output_query = self::$mysqli->query($final_query);


		if (!$output_query === TRUE) {
		    return self::$mysqli->error;
		}

		return true;
	}

	public function delete(){
		############### Conecting To DB
		self::connect();

		$query_where = self::decWhere();
		
		$final_query =  "DELETE FROM ".self::$tb_name . " " . $query_where . ";";

		### RUN QUERY
		$output_query = self::$mysqli->query($final_query);


		if (!$output_query === TRUE) {
		    return self::$mysqli->error;
		}

		return true;
	}

	public function insert($data){
		############### Conecting To DB
		self::connect();

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

				### BUILD QUERY
				$final_query = "INSERT INTO tb_auth " . $insert_data_key . " VALUES " . $insert_data_value . ";";

				$no_par++;

				### RUN QUERY
				$output_query = self::$mysqli->query($final_query);


				if (!$output_query === TRUE) {
				    return self::$mysqli->error;
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

			### BUILD QUERY
			$final_query = "INSERT INTO " . self::$tb_name . " ". $insert_data_key ."	
							VALUES " . $insert_data_value . ";";

			### RUN QUERY
			$output_query = self::$mysqli->query($final_query);


			if (!$output_query === TRUE) {
			    return self::$mysqli->error;
			} else {
				return true;
			}

		}


			


		//return $final_query;
	}
}