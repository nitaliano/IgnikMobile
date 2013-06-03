<?php 
/**
 * @author Nick Italiano
 * This class handles all connections and queries to MySQL
 */

	class MySQLConnector{
		private $host;
		private $user;
		private $password;
		private $db;
		private $isConnected;
		
		public function __construct($host, $user, $password){
			$this->host = $host;
			$this->user = $user;
			$this->password = $password;
		}
		
		public function connect(){
			$this->isConnected = mysql_connect($this->host, $this->user, $this->password);
			
			if(!$this->isConnected){
				trigger_error(mysql_error(), E_USER_ERROR);
			}			
		}
		
		public function selectDB($db){
			$this->db = mysql_select_db($db);
			
			if(!$this->db){
				trigger_error(mysql_error(), E_USER_ERROR);
			}
		}
		
		public function executeQuery($query, $isInsert){
			if(!$this->isConnected){
				return null;				
			}
						
			$results = mysql_query($query);
						
			if (mysql_errno()) {
				echo mysql_errno()." : ".mysql_error()."\n";
			}
						
			if($isInsert == false){	
				return mysql_fetch_array($results);
			}
		}
		
		public function close(){
			if($this->isConnected){
				mysql_close();
			}
		}
	}
?>