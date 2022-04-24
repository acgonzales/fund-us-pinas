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

// $shops = $shop->getAllShops(); 
$shops = $shop->getAllConfirmedShops();

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <div class="container">

        <?php include('header.php'); ?>


        <div class="row" id="markettitle">
            <h1 id="marketplace">
                Marketplace
            </h1>
            <a data-toggle="modal" data-target="#myModal3" href="#" id="donate"> Register your shop!</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="modal fade" id="myModal3">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1>
                                    Registration Notice
                                </h1>
                            </div>
                            <div class="modal-body">
                                After filling up the registration form, you are required to donate to Lingap Baste Donation as the shop name you registered. After the admin's confirmation for your donation, your shop will be posted in the marketplace.
                            </div>
                            <div class="modal-footer">
                                <a href="registershop.php">Close</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                    <a href="marketinfo.php?shop=<?= $shop["shop_id"] ?>" class="btn btn-primary">See Details</a>
                </div>
            </div>
        <?php endforeach ?>
        <br><br><br><br><br><br><br>
        <br><br><br>
        
    </div>
    
    </div>
    
</body>

</html>