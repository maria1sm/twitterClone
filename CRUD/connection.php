<?php

$host = "localhost:3306";
$user = "root";
$pass = "root";

$bd = "social_network";

$connection=mysqli_connect($host, $user, $pass);

mysqli_select_db($connection, $bd);
?>