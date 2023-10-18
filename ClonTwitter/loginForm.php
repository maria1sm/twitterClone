<?php
require_once("CRUD/connection.php");

session_start();
if(isset($_SESSION["usuario"])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>

    <meta charset="UTF-8">
    <meta name="description" content="Este es un ejemplo crud">
    <meta name="keywords" content="html, css, bootstrap, js, portfolio, proyectos, php">
    <meta name="language" content="EN">
    <meta name="author" content="maria.sisamon@a.vedrunasevillasj.es">
    <meta name="robots" content="index,follow">
    <meta name="revised" content="Tuesday, February 28th, 2023, 23:00pm">
    <meta name="viewport" content="width=device-width, initial scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE-edge, chrome1">

    <!-- Añado la fuente Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"
        defer></script>

    <!-- My css -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!-- My scripts -->
    <!-- <script type="text/javascript" src="js/app.js" defer></script> -->

    <!-- Icono al lado del titulo -->
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg" type="image/xpng">

    <!-- Titulo -->
    <title>Twitter Clon</title>

</head>

<body>

    <?php if(!isset($_SESSION["usuario"])): ?>
    <div class="container d-flex flex-column justify-content-center">
        <img class="align-self-center mt-5" src="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg" height="45px" width="auto">
        <section class="align-items-center">
            <div class="container pb-4 pt-5">
                <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                        type="button" role="tab" aria-controls="pills-home" aria-selected="true"><h4>Login</h4><div></div></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                        type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><h4>Registro</h4><div></div></button>
                    </li>
                </ul>
                <div class="tab-content d-flex justify-content-center w-100" id="pills-tabContent">
                    <div class="tab-pane fade show active h-100 w-75" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                        tabindex="0">
                        <!------------------------------------------ LOGIN ------------------------------------------>

                        <div id="login" class="container my-3"> 
                            <?php if(isset($_SESSION["error_login"])):?>
                                <div class="alert alert-warning" role="alert">
                                    <?php print_r($_SESSION["error_login"]); ?>
                                </div>
                            <?php endif ?>
                            <?php if(isset($_SESSION["errores"])) :?>
                                <?php foreach ($_SESSION["errores"] as $key => $value) :?>
                                    <div class="alert alert-warning" role="alert">
                                        <?= $value ?>
                                    </div>
                                <?php endforeach ?>
                                <?php 
                                    //var_dump($_SESSION["errores"]);
                                    session_unset();
                                ?>
                            <?php endif ?>
                            
                            <?php if(isset($_SESSION["completado"])): ?>
                                <div class="alert alert-success" role="alert">
                                    Registro completado
                                </div>
                                <?php $_SESSION["completado"] = null; ?>
                            <?php endif ?>
                            <form action="login/login.php" method="POST" class="mt-3 mx-auto">
                                <fieldset class="form-row reset p-4 align-items-center border border-0 rounded" id="login-card">
                                    
                                    <div class="form-group row g-3 mt-1 mx-auto">
                                        <label for="mail" class="col-md-2 col-form-label">Email / User:</label>
                                        <div class="col input-group mb-2">
                                            <input type="text" id="mail" class="form-control text-info" name="mail"/>
                                        </div>
                                    </div>

                                    <div class="form-group row g-3 mt-1 mx-auto">
                                        <label for="pass" class="col-md-2 col-form-label">Password:</label>
                                        <div class="col-sm-10">
                                            <input type="password" id="pass" class="form-control text-info" name="pass" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                                                title="Debe contener al menos un número y una mayúscula y una minúscula, y al menos 8 o más carácteres"/>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2 mx-auto d-grid col-6">
                                        <input id="sendBttn2" class="btn btn-primary btn-lg" type="submit" value="Send" name="submit"/>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <?php 
                            if(isset($_SESSION["error_login"])){
                                $_SESSION["error_login"] = null;
                                //session_unset($_SESSION["error_login"]);
                            }
                        ?>
                    </div>
                    <div class="tab-pane fade h-100  w-75" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                        tabindex="0">
                        <div id="registro" class="container">
                            <form action="login/registro.php" method="POST" class="mt-4 mx-auto">
                                <fieldset class="form-row reset p-4 align-items-center border border-0 border-round rounded" id="register-card">
                                    
                                    <div class="form-group row g-3 mt-1 mx-auto">
                                        <label for="username" class="col-md-2 col-form-label">Username:</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="username" class="form-control text-info" name="username" required />
                                        </div>
                                    </div>

                                    <div class="form-group row g-3 mt-1 mx-auto">
                                        <label for="mail" class="col-md-2 col-form-label">Email:</label>
                                        <div class="col input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">@</div>
                                            </div>
                                            <input type="email" id="mail" class="form-control text-info" name="mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"/>
                                        </div>
                                    </div>

                                    <div class="form-group row g-3 mt-1 mx-auto">
                                        <label for="password" class="col-md-2 col-form-label">Password:</label>
                                        <div class="col-sm-10">
                                            <input type="password" id="password" class="form-control text-info" name="password" required 
                                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                                            title="Debe contener al menos un número y una mayúscula y una minúscula, y al menos 8 o más carácteres"/>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2  d-grid col-6 mx-auto">
                                        <input id="sendBttn" class="btn btn-primary btn-lg" type="submit" value="Send" name="submit"/>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                        <?php 
                            if(isset($_SESSION["errores"])){
                                //$_SESSION["errores"] = null;
                                session_unset();
                            }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>          
    <?php endif?>
    
</body>

</html>