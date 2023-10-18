<?php 
include_once("CRUD/connection.php");

session_start();
if(!isset($_SESSION["usuario"])){
    header("Location: loginForm.php");
}
?>
<?php

$userID = $_SESSION['usuario']['id'];
$userName = $_SESSION['usuario']['username'];

$sql = "SELECT * FROM users WHERE id = '$userID'";
$res = mysqli_query($connection, $sql);

//tweets
$sql2 = "SELECT *
        FROM publications as pub
        JOIN users as us
        ON pub.userId = us.id
        WHERE pub.userId
        IN (SELECT userToFollowId
            FROM follows
            WHERE users_Id = '$userID')
        OR userId = '$userID'
        ORDER BY pub.id DESC";
$res2 = mysqli_query($connection, $sql2);

//count follows
$sql_siguiendo = "SELECT COUNT(*)
        FROM follows
        WHERE users_id = $userID";
$siguiendoCount = mysqli_query($connection, $sql_siguiendo);
$siguiendo = (mysqli_fetch_row($siguiendoCount))[0];
$_SESSION['following'] = $siguiendo;

$sql_seguidores = "SELECT COUNT(*)
        FROM follows
        WHERE userToFollowId = $userID";
$seguidoresCount = mysqli_query($connection, $sql_seguidores);
$seguidores = (mysqli_fetch_row($seguidoresCount))[0];
$_SESSION['followers'] = $seguidores;
            

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- My css -->
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"
        defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Icon -->
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg" type="image/xpng">
    <title>Inicio / Twitter Clon</title>
</head>
<body>
   <div class="sticky-top m-0">
        <header class="d-flex flex-row p-2 justify-content-center mt-0">
            <div class="w-100">
                <div class="w-100" >
                    <a data-bs-toggle="offcanvas" href="#offcanvasNav" role="button" aria-controls="offcanvasExample">
                        <img class="rounded-circle" height="35px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                    </a>
                </div>
                <a href="#" id="logo" class="align-self-center col-auto"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg" height="21px" width="auto"></a>
                <div class="w-100"></div>
            </div>
        </header>
        <hr class="m-0"/>
    </div>
<!-- Body/Tweets -->    
    <section class="section-main container p-0">
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
                    <a class="me-2" href="paginas/profile.php?id=<?= $tweet['userId'] ?>">
                        <img class="rounded-circle" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                    </a>
                    <a class="tw-name my-0 fw-bold" href="paginas/profile.php?id=<?= $tweet['userId'] ?>"><?=$tweet['username']?></a>
                    <p class="my-0 mx-2">·</p>
                    <p class="my-0">1h</p>
                </div>
                <div>
                    <p class="ms-5 mb-2"><?=$tweet['text']?></p>
                </div>
            </div>
        <hr class="m-0">
        <?php endwhile ?>
        <button type="button" class="post rounded-circle d-flex justify-content-center align-items-center position-fixed bottom-0 end-0 m-4" data-bs-toggle="modal" data-bs-target="#modalTweet">
            <svg id="svg-post">
                <g><path d="M23 3c-6.62-.1-10.38 2.421-13.05 6.03C7.29 12.61 6 17.331 6 22h2c0-1.007.07-2.012.19-3H12c4.1 0 7.48-3.082 7.94-7.054C22.79 
                10.147 23.17 6.359 23 3zm-7 8h-1.5v2H16c.63-.016 1.2-.08 1.72-.188C16.95 15.24 14.68 17 12 17H8.55c.57-2.512 1.57-4.851 3-6.78 2.16-2.912 
                5.29-4.911 9.45-5.187C20.95 8.079 19.9 11 16 11zM4 9V6H1V4h3V1h2v3h3v2H6v3H4z">
                </path></g>
            </svg>
        </button>
<!-- Envío Tweet -->
        
<!-- Tweet: Modal -->
        <div class="modal" id="modalTweet" tabindex="-1" aria-labelledby="modalTweetLabel" style="display: none;" aria-hidden="true">
        <!--<div class="modal show" id="modalTweet" tabindex="-1" aria-labelledby="modalTweetLabel" style="display: block;" aria-modal="true">-->
            <div class="modal-dialog modal-fullscreen-md-down">
                <div class="modal-content">
                    <form action="paginas/twittear.php" method="POST" >
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

    
<!-- Nav Sidebar-->

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNav" aria-labelledby="offcanvasExampleLabel" aria-hidden="true">
  <div class="offcanvas-header d-flex flex-column align-items-start">
    <a class="me-2" href="paginas/profile.php?id=<?= $userID ?>">
        <img class="rounded-circle" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
    </a>
    <p class="mt-2 fw-bold"><?="@".$userName?></p>
    <div class="following d-flex flex-row gap-2">
        <div id="follows"><p><span class="fw-bold"><?=$_SESSION['following']." "?></span>Siguiendo</p></div>
        <div id="followers"><p><span class="fw-bold"><?=$_SESSION['followers']." "?></span>Seguidores</p></div>
    </div>
  </div>
  <div class="offcanvas-body p-0 align-items-start">
    <div>
      
    </div>
    <div class="dropdown mt-3">
      <button class="btn-nav btn dropdown-toggle bg-transparent border-0 w-100 rounded-0 p-3 d-flex flex-row justify-content-between align-items-center" type="button" data-bs-toggle="dropdown">
        <p class="m-0 fw-bold">Configuración</p>
        <i><svg viewBox="0 0 24 24"><path d='M3.543 8.96l1.414-1.42L12 14.59l7.043-7.05 1.414 1.42L12 17.41 3.543 8.96z'></path></svg></i>
      </button>
      <ul class="dropdown-menu rounded-0 m-0 border-0 w-100">
        <li>
            <a class="dropdown-item mb-1 btn-nav d-flex flex-row align-items-center" href="#">
            <svg viewBox="0 0 24 24" aria-hidden="true" class="me-1"><g><path d="M20 12h2v6.5c0 1.38-1.12 2.5-2.5 2.5h-15C3.12 21 2 19.88 2 18.5v-13C2 4.12 3.12 3 4.5 3H11v2H4.5c-.28 0-.5.22-.5.5v13c0 
             .28.22.5.5.5h15c.27 0 .5-.22.5-.5V12zm2.31-6.78l-6.33 7.18c-.2 2.02-1.91 3.6-3.98 3.6H8v-4c0-2.07 1.58-3.78 3.6-3.98l7.18-6.33c.99-.88 2.49-.83
              3.43.1.93.94.98 2.44.1 3.43zm-1.52-2.01c-.19-.19-.49-.2-.69-.02l-6.08 5.36c.59.35 1.08.84 1.43 1.43l5.36-6.08c.18-.2.17-.5-.02-.69z"></path></g>
            </svg>
            <svg viewBox="0 0 24 24" aria-hidden="true" id="pincel"><g><path d="M14 12c0-1.1-.9-2-2-2-1.11 0-2 .9-2 2v2h2c1.1 0 2-.9 2-2z"></path></g></svg>
            Modo de color
            </a>
        </li>
        <li>
            <a class="dropdown-item mb-1 btn-nav d-flex flex-row align-items-center" href="login/logout.php">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="me-1"><g><path d="M4 4.5C4 3.12 5.12 2 6.5 2h11C18.88 2 20 3.12 20 4.5v15c0 1.38-1.12 2.5-2.5 2.5h-11C5.12 22 4 20.88 4 19.5V16h2v3.5c0 .28.22.5.5.5h11c.28 0 .5-.22.5-.5v-15c0-.28-.22-.5-.5-.5h-11c-.28 0-.5.22-.5.5V8H4V4.5zm6.95 3.04L15.42 12l-4.47 4.46-1.41-1.42L11.58 13H2v-2h9.58L9.54 8.96l1.41-1.42z"></path></g></svg>
                Cerrar sesión
            </a>
        </li>
      </ul>
    </div>
  </div>
</div>

</body>
</html>