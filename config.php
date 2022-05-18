<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



function databaseConnect(){
	define('DB_SERVER','localhost');
	define('DB_USERNAME','root');
	define('DB_PASSWORD','root');
	define('DB_NAME','testdb');

	$link= mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	return $link;
}

function listEmployees(){
	$link = databaseConnect();
	$sql = "SELECT id, name, address,salary FROM employee";
	$result = $link->query($sql);
	$link->close();
	return $result;
}

?>