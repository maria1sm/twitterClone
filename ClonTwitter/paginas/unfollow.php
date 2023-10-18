<?php 
include_once("../CRUD/connection.php");

session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: ../loginForm.php");
}
$userToUnFollow=$_GET["id"];
$userLogged= $_SESSION['usuario']['id'];

$sql="DELETE FROM follows 
    WHERE users_id = $userLogged
    AND userToFollowId = $userToUnFollow";
$query = mysqli_query($connection, $sql);

if($query){
    Header("Location: profile.php?id=$userToUnFollow");
}

?>