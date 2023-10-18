<?php 

if (isset($_POST["submit"])) {
    require_once("../CRUD/connection.php");
    session_start();

    //Recoger los datos
    $username = isset($_POST["username"]) ? mysqli_real_escape_string($connection, $_POST["username"]) : false;
    $mail = isset($_POST["mail"]) ? mysqli_real_escape_string($connection, trim($_POST["mail"])) : false;
    $pass = isset($_POST["password"]) ? mysqli_real_escape_string($connection, $_POST["password"]) : false;
    //var_dump($_POST);

    $arrayErrores = array();
    //Hacemos validadores necesarios
    if (!empty($username) && !is_numeric($username)) {
        $usernameValidado = true;
    } else {
        $usernameValidado = false;
        $arrayErrores["username"] = "El username no es valido";
    }

    $sqlUser = "SELECT Count(*) FROM users WHERE username= '{$username}'";
    $queryCount = mysqli_query($connection, $sqlUser);
    $userCount = (mysqli_fetch_row($queryCount))[0];

    if ($usernameValidado && ($userCount > 0)) {
        $usernameValidado = false;
        $arrayErrores["username"] = "Este username ya está en uso";
    }

    if (!empty($mail) && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $mailValidado = true;
    } else {
        $mailValidado = false;
        $arrayErrores["mail"] = "El mail no es valido";
    }

    $sqlEmail = "SELECT Count(*) FROM users WHERE email= '{$mail}'";
    $queryCount = mysqli_query($connection, $sqlEmail);
    $mailCount = (mysqli_fetch_row($queryCount))[0];

    if ($mailValidado && ($mailCount > 0)) {
        $mailValidado = false;
        $arrayErrores["mail"] = "Este mail ya ha sido registrado";
    }

    if (!empty($pass)) {
        $passValidado = true;
    } else {
        $passValidado = false;
        $arrayErrores["password"] = "El password no es valido";
    }

    $guardarUsuario = false;
    if(count($arrayErrores) == 0) {
        $guardarUsuario = true;
        
        $passSegura = password_hash($pass, PASSWORD_BCRYPT, ["cost" => 4]);
        //password_verify($pass, $passSegura);
        
        $sql = "INSERT INTO users VALUES(0, '$username', '$mail', '$passSegura', NULL,CURDATE());";
        $guardar = mysqli_query($connection, $sql);

        if ($guardar) {
            $_SESSION["completado"] = "Registro completado";
        } else {
            $_SESSION["errores"]["general"] = "Fallo en el registro";
        }
    } else {
        $_SESSION["errores"] = $arrayErrores;
    }
    header("Location: ../loginForm.php");
}
?>