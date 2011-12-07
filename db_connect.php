<?php
	session_start();

	$myUserName = "ycheng06";
	$myPassword = "Jason914";
	$myDatabase = "ycheng06";
	$myHost = "mysql-user";
	$db = mysql_connect($myHost, $myUserName, $myPassword);
	if (!$db) {
		die('Cannot connect to the database: ' . mysql_error());
	}
	else {
		mysql_select_db($myDatabase) or die("Unable to select database");
	}
?>
