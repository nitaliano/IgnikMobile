<?php
/**
 * @author: Nick Italiano
 */

	include_once "../lib/Contact.php";
	
	error_reporting(E_ALL ^ E_DEPRECATED);
	
	$flag = true;
	
	if(!isset($_GET['name'])) $flag = false;
	if(!isset($_GET['email']) || !isEmail($_GET['email'])) $flag = false;
	if(!isset($_GET['budget'])) $flag = false;
	if(!isset($_GET['timeline'])) $flag = false;
	
	if($flag){
		$name = $_GET['name'];
		$emailField = $_GET['email'];
		$budget = $_GET['budget'];
		$timeline = $_GET['timeline'];
		$comments = $_GET['comment'];
		
		$email = new Contact($name, $emailField, $budget,$timeline, $comments);
		$email->create();
		$email->send();
		echo 0;
	} else {
		echo 1;
	}
	
	function isEmail($email){
		if(ereg('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$', $email)){
			return true;
		}
		return false;
	}
?>