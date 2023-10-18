<?php 
include_once("../CRUD/connection.php");

session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: ../loginForm.php");
}
$userToFollow=$_GET["id"];
$userLogged= $_SESSION['usuario']['id'];

$sql="INSERT INTO follows VALUES ($userLogged, $userToFollow)";
$query = mysqli_query($connection, $sql);

if($query){
    Header("Location: profile.php?id=$userToFollow");
}

?>