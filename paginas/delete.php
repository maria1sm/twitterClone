<?php 
include_once("../CRUD/connection.php");

session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: ../loginForm.php");
}
$idTweet=$_GET["id"];

$sql="DELETE FROM publications WHERE id='$idTweet'";
$query = mysqli_query($connection, $sql);
$userID=$_SESSION['usuario']['id'];
if($query){
    Header("Location: profile.php?id=$userID");
} else {
    $_SESSION["errorDelete"] = "Error al borrar el tweet";
    Header("Location: profile.php?id=$userID");
}

?>