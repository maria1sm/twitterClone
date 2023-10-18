<?php 

if (isset($_POST["submit"])) {
    require_once("../CRUD/connection.php");
    session_start();

    if(isset($_POST["texto"])) {
        $userID = $_SESSION['usuario']['id'];
        $text = trim($_POST["texto"]);

        $sql = "INSERT INTO publications VALUES(0, '$userID', '$text',CURDATE());";
        $enviar = mysqli_query($connection, $sql);
        if ($enviar) {
            $_SESSION["enviado"] = "Tweet publicado";
        } else {
            $_SESSION["errorEnvio"] = "Fallo en el envío del tweet";
        }
    } else {
        $_SESSION["errorEnvio"] = "El tweet no se pudo enviar";
    }
}
header("Location: ../index.php");
?>