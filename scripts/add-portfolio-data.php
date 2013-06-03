<?php
/**
 * @author: Nick Italiano
 */
 
	include_once "../lib/MySQLInfo.php";
	include_once "../lib/MySQLConnector.php";
	
	$mysql = new MySQLConnector(MySQLInfo::HOST, MySQLInfo::USER, MySQLInfo::PASSWORD);
	$imageList = array();
	
	if ($opendir = opendir('../portfolio-images/')){

		$mysql->connect();
		$mysql->selectDB(MySQLInfo::DB);
				
		while( ($image = readdir($opendir)) != false ){
			if( $image != "." && $image != ".."){
				array_push($imageList, $image);
			}
		}
		
		for($i = 0; $i < count($imageList); $i++){
			$query = "select filename from Likes where filename='".$imageList[$i]."'";
			$result = $mysql->executeQuery($query, false);

			if(!$result){
				$insert = "insert into Likes (filename, likes) values('".$imageList[$i]."', 0)";
				$mysql->executeQuery($insert, true);
			}
		}
		
		$mysql->close();
	}
		
?>