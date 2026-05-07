<?php

//connecting to the database
$host = "localhost";
$user = "root";
$password = "";
$DB = "Maluti";

$check = mysqli_connect($host,$user,$password,$DB);

if($check === false){
	die("There is connection error!!!");
}




?>