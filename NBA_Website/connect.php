<?php
$host = 'localhost';
$dbname = 'playoff_try'; 
$user = 'root'; 
$pass = ''; 

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

?>