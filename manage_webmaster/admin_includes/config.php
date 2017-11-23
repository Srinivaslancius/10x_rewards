<?php
session_start(); 
date_default_timezone_set("Asia/Kolkata");

$setcon = 2;
if($setcon == 1) {
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "";
} else {
    $servername = "192.168.0.104";  
    $username = "root";
    $password = "root";
    $dbname = "10x_rewards";
}  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$base_url = "http://192.168.0.104/10x_rewards/";
 
?>