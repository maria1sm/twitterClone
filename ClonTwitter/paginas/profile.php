<?php 
include_once("../CRUD/connection.php");

session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: ../loginForm.php");
}
?>
<?php

$userID = $_GET['id'];
$userLogged = $_SESSION['usuario']['id'];

$sql = "SELECT * FROM users WHERE id = '$userID'";
$res = mysqli_query($connection, $sql);
$userInfo = mysqli_fetch_assoc($res);
//tweets
$sql2 = "SELECT pub.id as pubId, userId, text, pub.createDate as pubDate, username, description, us.createDate as userDate
        FROM publications as pub
        JOIN users as us
        ON pub.userId = us.id
        WHERE userId = '$userID'
        ORDER BY pub.id DESC";
$res2 = mysqli_query($connection, $sql2);
$sql3 = "SELECT COUNT(*)
        FROM follows
        WHERE users_id = $userLogged
        AND userToFollowId = $userID";
$count = mysqli_query($connection, $sql3);
$follows = (mysqli_fetch_row($count))[0];
if ($follows > 0) {
    $follows = true;
} else {
    $follows = false;
}
//count follows
$sql_siguiendo = "SELECT COUNT(*)
        FROM follows
        WHERE users_id = $userID";
$siguiendoCount = mysqli_query($connection, $sql_siguiendo);
$siguiendo = (mysqli_fetch_row($siguiendoCount))[0];


$sql_seguidores = "SELECT COUNT(*)
        FROM follows
        WHERE userToFollowId = $userID";
$seguidoresCount = mysqli_query($connection, $sql_seguidores);
$seguidores = (mysqli_fetch_row($seguidoresCount))[0];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- My css -->
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"
        defer></script>
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg" type="image/xpng">
    <title>Usuario / Twitter Clon</title>
</head>
<body>
    
        <!-- Body/Tweets -->    
    <section class="section-main container p-0">
            <div id="profile-header" class="px-2 d-flex align-items-center sticky-top">
                <a href="../index.php" class="bg-transparent btn-plus" style="font-weight: bold;">
                    <div class="arrow-bg">
                        <svg viewBox="0 0 24 24" class="arrow"><g><path d="M7.414 13l5.043 5.04-1.414 1.42L3.586 12l7.457-7.46 1.414 1.42L7.414 11H21v2H7.414z"></path></g></svg>
                    </div>
                </a>
                <div class="d-flex flex-column justify-content-center ms-4">
                    <p class="fw-bold m-0"><?= $userInfo['username']?></p>
                    <p class="m-0" id="post-n"><?="1238 posts"?></p>
                </div>
            </div>
        <header>
            
            <div class="bg-secondary" id="header-picture">

            </div>
            <div>
                <img id="profile-pic" class="rounded-circle m-3" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                <?php if (($userID == $_SESSION['usuario']['id'])):?>
                    <a href="edit.php?id=<?=$userLogged?>" class="text-decoration-none ms-auto m-3 p-2 btn-perfil d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#modalEdit">Editar</a>
                <?php elseif (!$follows) :?>
                    <a href="follow.php?id=<?=$userID?>" class="text-decoration-none ms-auto m-3 p-2 btn-perfil d-flex align-items-center justify-content-center">Seguir</a>
                <?php else :?>
                    <a href="unfollow.php?id=<?=$userID?>" class="btn-unfollow text-decoration-none ms-auto m-3 p-2 btn-perfil d-flex align-items-center justify-content-center"><span id="following">Siguiendo</span><span id="unfollow">Dejar de seguir</span></a>
                <?php endif ?>
            </div>
            <div class="p-3 d-flex flex-column">
                <p class="m-0 fw-bold fs-5"><?=$userInfo['username']?></p>
                <p class="mx-0 mt-2 mb-1"><?=$userInfo['description']?></p>
                <div class="following d-flex flex-row gap-2">
                    <a href="followersTab.php?id=<?=$userID?>" class="tw-name" id="follows"><p><span class="fw-bold"><?=$siguiendo." "?></span>Siguiendo</p></a>
                    <a href="followersTab.php?id=<?=$userID?>" class="tw-name" id="followers"><p><span class="fw-bold"><?=$seguidores." "?></span>Seguidores</p></a>
                </div>
            </div>
            <hr class="m-0"/>
        </header>
        <!--Modal Editar-->
        <div class="modal" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" style="display: none;" aria-hidden="true">
        <!--<div class="modal show" id="modalTweet" tabindex="-1" aria-labelledby="modalTweetLabel" style="display: block;" aria-modal="true">-->
            <div class="modal-dialog modal-fullscreen-md-down">
                <div class="modal-content">
                    <form action="edit.php" method="POST" >
                        <div class="modal-header">
                            <button type="button" class="bg-transparent" style="font-weight: bold;" data-bs-dismiss="modal" aria-label="Close">
                                <svg viewBox="0 0 24 24" class="arrow"><g><path d="M7.414 13l5.043 5.04-1.414 1.42L3.586 12l7.457-7.46 1.414 1.42L7.414 11H21v2H7.414z"></path></g></svg>
                            </button>
                            <p class="fw-bold align-self-center me-auto ps-3 my-0">Editar perfil</p>
                            <input id="editTweet" type="submit" value="Guardar" name="submit"/>
                        </div>
                        <div class="modal-body mt-2 d-flex">
                            <fieldset id="editar-perfil"  class="form-row reset align-items-center border border-0 rounded w-100">
                                <div class="form-group row g-3 mt-0 mx-auto">
                                    <label class="m-1"for="texto-bio">Biografía</label>
                                    <textarea class="form-control m-0 border-0" id="texto-bio"name="texto-bio" rows="4" maxlength="153" minlength="1" required></textarea>                   
                                </div>
                            </fieldset>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>           
    
        <?php
            /*if(isset($_SESSION["enviado"])) {
                var_dump($_SESSION["enviado"]);
            }
            if(isset($_SESSION["errorEnvio"])) {
                var_dump($_SESSION["errorEnvio"]);
            }
            unset($_SESSION["enviado"]);
            unset($_SESSION["errorEnvio"]);*/
        ?>
        <?php while($tweet = mysqli_fetch_assoc($res2)) :?>
            
            <div class="tweet p-3">
                <div class="d-flex flex-row">
                    <a class="me-2" href="profile.php?id=<?= $tweet['userId'] ?>">
                        <img class="rounded-circle" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                    </a>
                    <a class="tw-name my-0 fw-bold" href="profile.php?id=<?= $tweet['userId'] ?>"><?=$tweet['username']?></a>
                    <p class="my-0 mx-2">·</p>
                    <p class="my-0">1h</p>
                    <!-- Default dropstart button -->
                    <div class="btn-group dropstart ms-auto">
                        <button class="d-flex flex-row align-items-start btn-nav bg-transparent btn-plus dropstart" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="p-2">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><g><path d="M3 12c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2zm9 
                                2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm7 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"></path></g></svg>
                            </div>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if ($userID == $_SESSION['usuario']['id']) :?>
                                <li>
                                    <a class="delete-tweet fw-bold p-2 text-decoration-none" href="delete.php?id=<?=$tweet['pubId']?>">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><g><path d="M16 6V4.5C16 3.12 14.88 2 13.5 2h-3C9.11 2 8 3.12 
                                    8 4.5V6H3v2h1.06l.81 11.21C4.98 20.78 6.28 22 7.86 22h8.27c1.58 0 2.88-1.22 3-2.79L19.93 8H21V6h-5zm-6-1.5c0-.28.22-.5.5-.5h3c.27 0 
                                    .5.22.5.5V6h-4V4.5zm7.13 14.57c-.04.52-.47.93-1 .93H7.86c-.53 0-.96-.41-1-.93L6.07 8h11.85l-.79 11.07zM9 17v-6h2v6H9zm4 0v-6h2v6h-2z"></path></g></svg>
                                    <span class="ps-2" >Eliminar</span>
                                    </a>
                                </li>
                            <?php else :?>
                                <li>
                                    Nada que ver por aquí...
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
                <div>
                    <p class="ms-5 mb-2"><?=$tweet['text']?></p>
                </div>
            </div>
        <hr class="m-0">
        <?php endwhile ?>
<!-- Envío Tweet -->
       
        <button type="button" class="post rounded-circle d-flex justify-content-center align-items-center position-fixed bottom-0 end-0 m-4" data-bs-toggle="modal" data-bs-target="#modalTweet">
            <svg id="svg-post">
                <g><path d="M23 3c-6.62-.1-10.38 2.421-13.05 6.03C7.29 12.61 6 17.331 6 22h2c0-1.007.07-2.012.19-3H12c4.1 0 7.48-3.082 7.94-7.054C22.79 
                10.147 23.17 6.359 23 3zm-7 8h-1.5v2H16c.63-.016 1.2-.08 1.72-.188C16.95 15.24 14.68 17 12 17H8.55c.57-2.512 1.57-4.851 3-6.78 2.16-2.912 
                5.29-4.911 9.45-5.187C20.95 8.079 19.9 11 16 11zM4 9V6H1V4h3V1h2v3h3v2H6v3H4z">
                </path></g>
            </svg>
        </button>
 
<!-- Tweet: Modal -->
        <div class="modal" id="modalTweet" tabindex="-1" aria-labelledby="modalTweetLabel" style="display: none;" aria-hidden="true">
        <!--<div class="modal show" id="modalTweet" tabindex="-1" aria-labelledby="modalTweetLabel" style="display: block;" aria-modal="true">-->
            <div class="modal-dialog modal-fullscreen-md-down">
                <div class="modal-content">
                    <form action="twittear.php" method="POST" >
                        <div class="modal-header">
                            <button type="button" class="bg-transparent" style="font-weight: bold;" data-bs-dismiss="modal" aria-label="Close">
                                <svg viewBox="0 0 24 24" class="arrow"><g><path d="M7.414 13l5.043 5.04-1.414 1.42L3.586 12l7.457-7.46 1.414 1.42L7.414 11H21v2H7.414z"></path></g></svg>
                            </button>
                            
                            <input id="sendTweet" type="submit" value="Twittear" name="submit"/>
                        </div>
                        <div class="modal-body mt-2 d-flex">
                            <img class="rounded-circle" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                            <fieldset class="form-row reset align-items-center border border-0 rounded w-100">
                                
                                <div class="form-group row g-3 mt-0 mx-auto">
                                    <textarea class="form-control m-0 pt-5 border-0" id="texto" name="texto" rows="4" placeholder="¡¿Qué está pasando?!" maxlength="273" minlength="1" required></textarea>
                                </div>
                            </fieldset>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </section>
</body>
</html>