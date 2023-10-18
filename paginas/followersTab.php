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
$sql3 = "SELECT fol.users_Id as seguidor, username, description
        FROM follows as fol
        JOIN users as us
        ON us.id = fol.users_Id
        WHERE fol.userToFollowId = $userID";
$seguidores = mysqli_query($connection, $sql3);
$sql4 = "SELECT fol.userToFollowId as siguiendo, username, description
        FROM follows as fol
        JOIN users as us
        ON us.id = fol.userToFollowId
        WHERE users_Id = $userID";
$siguiendo = mysqli_query($connection, $sql4);
/*$sql4 = "SELECT *
        FROM follows as fol
        JOIN users as us
        ON us.id = fol.users_Id
        WHERE users_id = $userLogged
        AND userToFollowId = $userID";
$siguiendo = mysqli_query($connection, $sql4);*/
       

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
                <a href="profile.php?id=<?=$userID?>" class="bg-transparent btn-plus" style="font-weight: bold;">
                    <div class="arrow-bg">
                        <svg viewBox="0 0 24 24" class="arrow"><g><path d="M7.414 13l5.043 5.04-1.414 1.42L3.586 12l7.457-7.46 1.414 1.42L7.414 11H21v2H7.414z"></path></g></svg>
                    </div>
                </a>
                <div class="d-flex flex-column justify-content-center ms-4">
                    <p class="fw-bold m-0 pb-2"><?= $userInfo['username']?></p>
                </div>
            </div>
<!--Nav pills-->
            <ul class="nav nav-pills mb-3 d-flex justify-content-around" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-seguidores" type="button" role="tab" aria-controls="pills-seguidores" aria-selected="true"><p class="pb-2 m-0 fw-bold">Seguidores</p><div></div></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-siguiendo" type="button" role="tab" aria-controls="pills-siguiendo" aria-selected="false"><p class="pb-2 m-0 fw-bold">Siguiendo</p><div></div></button>
                </li>
            </ul>
<!--Nav content-->
            <div class="tab-content d-flex justify-content-center align-items-center w-100" id="pills-tabContent">
                <div class="tab-pane fade show active d-flex flex-column align-items-center h-100 w-100" id="pills-seguidores" role="tabpanel" aria-labelledby="pills-seguidores-tab" tabindex="0">
                    <?php while($profile = mysqli_fetch_assoc($seguidores)) :?>
                        <div class="tweet p-3 w-100">
                            <div class="d-flex flex-row">
                                <a class="me-2" href="profile.php?id=<?= $profile['seguidor'] ?>">
                                    <img class="rounded-circle" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                                </a>
                                <a class="tw-name my-0 fw-bold" href="profile.php?id=<?= $profile['seguidor'] ?>"><?=$profile['username']?></a>
                                <!-- Default dropstart button -->
                                <div class="btn-group dropstart ms-auto">
                                    <button class="d-flex flex-row align-items-start btn-nav bg-transparent btn-plus dropstart" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="p-2">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><g><path d="M3 12c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2zm9 
                                            2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm7 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"></path></g></svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="ms-5 mb-2"><?=$profile['description']?></p>
                            </div>
                        </div>
                        <hr class="m-0">
                    <?php endwhile ?>

                </div>
                <div class="tab-pane fade d-flex flex-column align-items-center h-100  w-100" id="pills-siguiendo" role="tabpanel" aria-labelledby="pills-siguiendo-tab" tabindex="0">
                    <?php while($profile = mysqli_fetch_assoc($siguiendo)) :?>
                        <div class="tweet p-3 w-100">
                            <div class="d-flex flex-row">
                                <a class="me-2" href="profile.php?id=<?= $profile['siguiendo'] ?>">
                                    <img class="rounded-circle" height="40px" src="https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png">
                                </a>
                                <a class="tw-name my-0 fw-bold" href="profile.php?id=<?= $profile['siguiendo'] ?>"><?=$profile['username']?></a>
                                <!-- Default dropstart button -->
                                <div class="btn-group dropstart ms-auto">
                                    <button class="d-flex flex-row align-items-start btn-nav bg-transparent btn-plus dropstart" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="p-2">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><g><path d="M3 12c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2zm9 
                                            2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm7 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"></path></g></svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="ms-5 mb-2"><?=$profile['description']?></p>
                            </div>
                            
                        </div>
                        <hr class="m-0">
                    <?php endwhile ?>
                </div>
            </div>
    </section>
            
</body>
</html>


