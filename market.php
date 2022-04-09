<?php

    session_start();

    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    include("classes/post.php");

    $login = new Login();
    $user_data = $login->check_login($_SESSION['fundus_userid']);

?>
<html>
    <head>
        <title>Market Page</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/market.css">
        <link rel="stylesheet" href="css/story.css">
        <!-- jQuery library -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">

            <?php include('header.php'); ?>
        
        
            <div class="row" id="markettitle">
                <h1 id="marketplace">
                    Marketplace
                </h1>
                <button id="donate" class="btn btn-primary">Register your shop!</button>
                </div>
                <br><br><br><br>
                    <div id="fundraise" class="card" style="width: 40rem;">
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div id="fundraise" class="card" style="width: 40rem;">
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div id="fundraise" class="card" style="width: 40rem;">
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div id="fundraise" class="card" style="width: 40rem;">
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div id="fundraise" class="card" style="width: 40rem;">
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <div id="fundraise" class="card" style="width: 40rem;">
                        <img class="card-img-top" src="..." alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br>
                    <br><br><br>
                </div>
        </div>
    </body>
</html>