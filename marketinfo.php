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

$shopId = isset($_GET["shop"]) ? $_GET["shop"] : -1;
$shopData = $shop->getShopById($shopId);

if (!$shopData) {
    header("Location: market.php");
    die;
}

?>

<html>

<head>
    <title>User Fundraising Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/donate.css">
    <!-- jQuery library -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <?php include("header.php"); ?>
        <br><br><br>
        <div class="row" id="userline">
            <h1><?= $shopData["name"] ?>
                <?php
                if ($user_data["user_id"] == $shopData["user_id"]) :
                ?>
                    <a href="/editshop.php?shop=<?= $shopId ?>" class="btn btn-info">Edit</a>
                    <form action="/delete-shop.php" method="POST" style="display: inline;">
                        <input type="hidden" name="shop_id" value="<?= $shopId ?>" />
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                <?php endif ?>
            </h1><br><br>

            <div class="col-md-7" id="userimage">
                <img src="<?= $shopData["image"] ?>" alt="user shop image">
            </div>
            <div class="col-md-5" id="moneyraised">
                <br><br>
                <span>
                    FB: <a href="<?= $shopData["fb_link"] ?>" target="_blank"><?= $shopData["fb_link"] ?? "N/A" ?></a>
                </span><br>
                <hr>
                <span>
                    IG: <a href="<?= $shopData["ig_link"] ?>" target="_blank"><?= $shopData["ig_link"] ?? "N/A" ?></a>
                </span><br>
                <hr>
                <span>
                    Shopee: <a href="<?= $shopData["shopee_link"] ?>" target="_blank"><?= $shopData["shopee_link"] ?? "N/A" ?></a>
                </span><br>
                <hr>
                <span>
                    Lazada: <a href="<?= $shopData["lazada_link"] ?>" target="_blank"><?= $shopData["lazada_link"] ?? "N/A" ?></a>
                </span><br>
                <hr>

            </div>

        </div> <br><br>
        <div class="row" id="receiverdetails">
            <h1 id="organizer">
                Organizer: <?= $shopData["first_name"] . " " . $shopData["last_name"] ?>
            </h1>
            <p id="details">
                <?= $shopData["description"] ?>
            </p>
        </div>
        <br><br><br><br>
    </div>



    <br><br>
    </div>
</body>

</html>