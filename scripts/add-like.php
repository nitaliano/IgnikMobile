<?php
/**
 * @author: Nick Italiano
 */

	include_once "../lib/MySQLInfo.php";
	include_once "../lib/MySQLConnector.php";
	
	$id = $_GET["key"];
	
	if($id){
		$mySQLConnector = new MySQLConnector(MySQLInfo::HOST, MySQLInfo::USER, MySQLInfo::PASSWORD);
		$mySQLConnector->connect();
		$mySQLConnector->selectDB(MySQLInfo::DB);
		$query = "update Likes set likes= likes + 1 where filename='".mysql_real_escape_string($id)."'";
		$mySQLConnector->executeQuery($query, true);
		$mySQLConnector->close();
	}
?>