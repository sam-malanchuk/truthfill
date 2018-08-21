<?php
	define('SERVER', 'localhost');
	define('USER', ''); // enter the database username here
	define('PASS', ''); // enter the database password here
	define('DB', ''); // enter the database name here
	class Connection{
		/**
		 * @var Resource 
		 */
		var $mysqli = null;

		function __construct(){
			try{
				if(!$this->mysqli){
					$this->mysqli = new MySQLi(SERVER, USER, PASS, DB);
					if(!$this->mysqli)
						throw new Exception('Could not create connection using MySQLi', 'NO_CONNECTION');
				}
			}
			catch(Exception $ex){
				echo "ERROR: ".$e->getMessage();
			} 
		}
	}
?>