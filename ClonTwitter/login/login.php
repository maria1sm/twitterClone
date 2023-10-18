<?php 
    require_once ("../CRUD/connection.php");
    session_start();

    if (isset($_POST["mail"]) && isset($_POST["pass"])) {
        $mail = trim($_POST["mail"]);
        $pass = $_POST["pass"];
    }
    if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM users WHERE email = '$mail'";
        $res = mysqli_query($connection, $sql);

        if ($res && mysqli_num_rows($res) == 1) {
            $usuario = mysqli_fetch_assoc($res);

            if (password_verify($pass, $usuario["password"])) {
                $_SESSION["usuario"] = $usuario;
                header("Location: ../index.php");
            } else {
                $_SESSION["error_login"] = "Login incorrecto";
                header("Location: ../loginForm.php");
            }
        } else {
            $_SESSION["error_login"] = "Login incorrecto";
            header("Location: ../loginForm.php");
        }
    } else {
        $sql = "SELECT * FROM users WHERE username = '$mail'";
        $res = mysqli_query($connection, $sql);

        if ($res && mysqli_num_rows($res) == 1) {
            $usuario = mysqli_fetch_assoc($res);

            if (password_verify($pass, $usuario["password"])) {
                $_SESSION["usuario"] = $usuario;
                header("Location: ../index.php");
            } else {
                $_SESSION["error_login"] = "Login incorrecto";
                header("Location: ../loginForm.php");
            }
        } else {
            $_SESSION["error_login"] = "Login incorrecto";
            header("Location: ../loginForm.php");
        }
    }
    
?>