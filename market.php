<?php

session_start();

require_once("classes/User.php");
require_once("classes/Shop.php");

$user = new User;
$shop = new Shop;

if (isset($_SESSION['fundus_userid'])) {
    $user_data = $user->getUserById($_SESSION['fundus_userid']);

    if (!$user_data) {
        unset($_SESSION["fundus_userid"]);
        header("Location: login.php");
        die;
    }
} else {
    header("Location: login.php");
    die;
}

$shops = $shop->getAllShops(); // $shop->getAllConfirmedShops

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
            <a href="registershop.php" id="donate"> Register your shop!</a>
        </div>
        <br><br><br><br>
        <?php
        foreach ($shops as $shop) :
        ?>
            <div id="fundraise" class="card" style="width: 40rem;">
                <img class="card-img-top" src="<?= $shop["image"] ?>" alt="Card image cap" style="height: 200px; width: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?= $shop["name"] ?></h5>
                    <p class="card-text"><?= $shop["description"] ?></p>
                    <a href="marketinfo.php?shop=<?= $shop["shop_id"] ?>" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        <?php endforeach ?>
        <br><br><br><br><br><br><br>
        <br><br><br>
    </div>
    </div>
</body>

</html>