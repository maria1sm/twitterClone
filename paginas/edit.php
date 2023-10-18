<?php 

if (isset($_POST["submit"])) {
    require_once("../CRUD/connection.php");
    session_start();

    if(isset($_POST["texto-bio"])) {
        $userID = $_SESSION['usuario']['id'];
        $desc = trim($_POST["texto-bio"]);

        $sql = "UPDATE users SET description = '$desc' WHERE id = $userID";
        $setBio = mysqli_query($connection, $sql);
        if ($setBio) {
            $_SESSION["bio"] = "Perfil cambiado";
        } else {
            $_SESSION["errorBio"] = "Fallo en el cambio de perfil";
        }
    } else {
        $_SESSION["errorEnvio"] = "El perfil no se pudo editar";
    }
}
header("Location: profile.php?id=$userID");
?>