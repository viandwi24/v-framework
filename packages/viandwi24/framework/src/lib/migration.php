<?php
namespace vifaframework\lib;

use PDO;
use PDOException;

Class migration {
	/**
	 *
	 * The host you will connect
	 * @var String
	 */
	public $host;
	/**
	 *
	 * The driver you will use to connect
	 * @var String
	 */
	public $driver;
	/**
	 *
	 * The user you will use to connect to a database
	 * @var String
	 */
	public $user;
	/**
	 *
	 * The password you will use to connect to a database
	 * @var String
	 */
	public $password;
	/**
	 *
	 * The database you will use to connect
	 * @var String
	 */
	public $dbName;
	/**
	 *
	 * String to connect to the database using PDO
	 * @var String
	 */
	public $dsn;

	/**
	 *
	 * Array with the tables of the database
	 * @var Array
	 */
	public $tables = array();

	/**
	 *
	 * Hold the connection
	 * @var ObjectConnection
	 */
	public $handler;
	/**
	 *
	 * Array to hold the errors
	 * @var Array
	 */
	public $error = array();

	/**
	 *
	 * The result string. String with all queries
	 * @var String
	 */
	public $final;
	private $args = array();

	public function __construct($args){
		$this->args = $args;

	}

	public function coba(){

		$args = $this->args;

		if(!$args['host']) $this->error[] = 'Parameter host missing';
		if(!$args['user']) $this->error[] = 'Parameter user missing';
		if(!isset($args['password'])) $args['password'] = '';
		if(!$args['database']) $this->error[] = 'Parameter database missing';
		if(!$args['driver']) $this->error[] = 'Parameter driver missing';


		$this->host = $args['host'];
		$this->driver = $args['driver'];
		$this->user = $args['user'];
		$this->password = $args['password'];
		$this->dbName = $args['database'];

		$this->final = 'CREATE DATABASE ' . $this->dbName.";\n\n";
		$this->final .= 'USE ' . $this->dbName.";\n\n";

		if($this->host=='localhost'){
			// We have a little issue in unix systems when you set the host as localhost
			$this->host = '127.0.0.1';
		}
		$this->dsn = $this->driver.':host='.$this->host.';dbname='.$this->dbName;

		$this->connect();
		$this->getTables();
		$this->generate();

		if(count($this->error)>0){
			return array('error'=>true, 'msg'=>$this->error);
		}
		return array('error'=>false, 'msg'=>$this->final);
	}
	private function generate(){
		foreach ($this->tables as $tbl) {
			$this->final .= $tbl['create'] . ";\n\n";
			$this->final .= $tbl['data']."\n\n\n";
		}
		$this->final .= "\n\n";
	}

	public function run_query($query){
		$args = $this->args;

		$this->host = $args['host'];
		$this->driver = $args['driver'];
		$this->user = $args['user'];
		$this->password = $args['password'];
		$this->dbName = $args['database'];

		if($this->host=='localhost'){
			// We have a little issue in unix systems when you set the host as localhost
			$this->host = '127.0.0.1';
		}
		$this->dsn = $this->driver.':host='.$this->host;
		
		$this->connect();

		try {
			$stmt = $this->handler->prepare($query); 
			$stmt->execute();
			return true;
		} catch (PDOException $e) {
			$this->handler = null;
			$this->error[] = $e->getMessage();
			return false;
		}

		$this->dsn = $this->driver.':host='.$this->host.';dbname='.$this->dbName;
		$this->connect();

		try {
			$query = <<<EOT
CREATE TABLE `tb_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kelas` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
EOT;
			$stmt = $this->handler->exec($query);
			return true;
		} catch (PDOException $e) {
			$this->handler = null;
			$this->error[] = $e->getMessage();
			return false;
		}

	}

	/**
	 *
	 * Connect to a database
	 * @uses Private use
	 */
	private function connect(){
		try {
			$this->handler = new PDO($this->dsn, $this->user, $this->password);
		} catch (PDOException $e) {
			echo "Koneksi Database Gagal.";
			exit(1);
			die();
		}
	}

	/**
	 *
	 * Get the list of tables
	 * @uses Private use
	 */
	private function getTables(){
		try {
			$stmt = $this->handler->query('SHOW TABLES');
			$tbs = $stmt->fetchAll();
			$i=0;
			foreach($tbs as $table){
				$this->tables[$i]['name'] = $table[0];
				$this->tables[$i]['create'] = $this->getColumns($table[0]);
				$this->tables[$i]['data'] = $this->getData($table[0]);
				$i++;
			}
			unset($stmt);
			unset($tbs);
			unset($i);

			return true;
		} catch (PDOException $e) {
			$this->handler = null;
			$this->error[] = $e->getMessage();
			return false;
		}
	}

	/**
	 *
	 * Get the list of Columns
	 * @uses Private use
	 */
	private function getColumns($tableName){
		try {
			$stmt = $this->handler->query('SHOW CREATE TABLE '.$tableName);
			$q = $stmt->fetchAll();
			$q[0][1] = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $q[0][1]);
			return $q[0][1];
		} catch (PDOException $e){
			$this->handler = null;
			$this->error[] = $e->getMessage();
			return false;
		}
	}

	/**
	 *
	 * Get the insert data of tables
	 * @uses Private use
	 */
	private function getData($tableName){
		try {
			$stmt = $this->handler->query('SELECT * FROM '.$tableName);
			$q = $stmt->fetchAll(PDO::FETCH_NUM);
			$data = '';
			foreach ($q as $pieces){
				foreach($pieces as &$value){
					$value = htmlentities(addslashes($value));
				}
				$data .= 'INSERT INTO '. $tableName .' VALUES (\'' . implode('\',\'', $pieces) . '\');'."\n";
			}
			return $data;
		} catch (PDOException $e){
			$this->handler = null;
			$this->error[] = $e->getMessage();
			return false;
		}
	}
}
