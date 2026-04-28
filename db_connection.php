<?php
$servername = "localhost";
$username = "root";
$password = "root"; 
$dbname = "rakkez_db";
$port = 8889;


$conn = @new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Erorr: " . $conn->connect_error);
}

?>