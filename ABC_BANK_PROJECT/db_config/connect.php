<?php

$host="localhost";
$user="root";
$pass="";
$db="login";

$connection = new mysqli($host,$user,$pass,$db);
date_default_timezone_set('Asia/Kolkata');

if($connection->connect_error){
    die("Failed to connect : ".$connection->connect_error);
}
else{
   // echo " Connection successful!";
}

?>