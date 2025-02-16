<?php

$server = "localhost";
$database = "userDB";
$username="root";
$password = "";
$conn = mysqli_connect($server,$username,$password,$database);


// Check connection
if(!$conn){
   die("<script>alert('connection Failed.')</script>");
}

?>