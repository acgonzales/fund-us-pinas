<?php

session_start();
// session_reset();

require_once("classes/User.php");
require_once("classes/Fundraiser.php");
require_once("classes/Donation.php");

$user = new User;

if (isset($_SESSION["fundus_userid"])) {
    $user_data = $user->getUserById($_SESSION['fundus_userid']);
    if ($user_data) {
        header("Location: user.php");
    } else {
        unset($_SESSION["fundus_userid"]);
    }
}

$fundraiser = new Fundraiser;
$donation = new Donation;

$fundraisers = $fundraiser->getNotExpiredFundraisers();

$foundationDonations = $donation->getFoundationDonations();
$totalFoundationDonations = $donation->getFoundationTotalDonations();



?>

<html>

<head>
    <title>Guest Landing Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/user.css">
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

        <?php include("login-header.php") ?>

        <div class="row" id="fundusabout"><br><br><br><br>
            <h1>About Fund Us Pinas</h1><br><br><br>
            <div id="about">
                <div class="col-md-1"><br>
                    <img id="logos" src="images/lingaplogo.jpg" alt="lingap baste logo">
                </div>
                <div class="col-md-3" id="aboutdetails">

                    <h3>Lingap Baste</h3><br>
                    <p>
                        Fund Us Pinas is a platform to help collect funds for projects of Lingap Baste A non-profit organization based in Tarlac City Philippines.
                    </p>
                </div>
                <div class="col-md-1"><br>
                    <img id="logos" src="images/userfundraise.png" alt="user fundraising logo">
                </div>
                <div class="col-md-3" id="aboutdetails">
                    <h3>User Fundraising</h3><br>
                    <p>
                        It also supports user fundraising, which allows them to organize their own fundraising activity. Easy access, and monitoring.
                    </p>
                </div>
                <div class="col-md-1"><br>
                    <img id="logos" src="images/anonymous.png" alt="anonymity user logo">
                </div>
                <div class="col-md-3" id="aboutdetails">
                    <h3>Anonymity</h3><br>
                    <p>
                        Fund Us Pinas supports users for their anonymity. When users donate, they have the option to be anonymous in their donations.
                    </p>
                </div>

            </div>

            <div id="about">
                <div class="col-md-1"><br>
                    <img id="logos" src="images/secure.png" alt="secure fund us pinas logo">
                </div>
                <div class="col-md-3" id="aboutdetails">
                    <h3>Secure</h3><br>
                    <p>
                        Admin monitors fundraising activities to protect against fraud. Fund us Pinas also uses gcash as a payment method for convenient transaction.
                    </p>
                </div>
                <div class="col-md-1"><br>
                    <img id="logos" src="images/setup.png" alt="easy setup logo">
                </div>
                <div class="col-md-3" id="aboutdetails">
                    <h3>Simple Setup</h3><br>
                    <p>
                        With only a user registration, and login feature, you can easily setup and post your fundraising activities.
                    </p>
                </div>
                <div class="col-md-1"><br>
                    <img id="logos" src="images/shop.png" alt="promote shop logo">
                </div>
                <div class="col-md-3" id="aboutdetails">
                    <h3>Promote Products</h3><br>
                    <p>
                        Fund us Pinas supports shops that donate and registered. By promoting their shop and products, this will help them increase their sales.
                    </p>
                </div>
            </div>

        </div>
        <br><br><br><br><br>
        <div class="row" id="moneyline">
            <br><br><br><br><br>
            <div class="col-md-7" id="tag">
                <h1 id="happen">LET'S MAKE IT HAPPEN!</h1>
                <h2 id="strong">Stronger Together!</h2>
                <p>
                    Donate to Lingap Baste - A non-profit organization based in Tarlac City Philippines. <br>
                    Support them to their continuous response in addressing the need to be always prepared for anything that is worse.
                </p>
            </div>
            <div class="col-md-4" id="money">
                <br>
                <span id="lingapdonation">
                    Lingap Baste Donation
                </span>
                <br>
                <hr>
                <br>
                <span>
                    <img id="logos2" src="images/moneyraised.png" alt="money raised logo">
                    Money raised:  <?= $totalFoundationDonations ?>
                </span>
                <hr>
                <span>
                    Recent Donations
                </span>
                <hr>
                <?php
                foreach ($foundationDonations as $donation) :
                    $name = "Anonymous";

                    if ($donation["is_anonymous"] != 1) {
                        $name = $donation["donator_type"] == "App\\Models\\User" ? $donation["first_name"] . " " . $donation["last_name"] : $donation["shop_name"];
                    }
                ?>
                    <span>
                        <?= $name ?> <br>
                        P<?= $donation["amount"] ?>
                    </span>
                    <hr>
                <?php endforeach ?>
                <form action="/new-anon-foundation-donation.php" method="POST">
                    <input name="amount" type="number" id="text" placeholder="Amount to Donate" required><br /><br />
                    <input type="submit" id="donate" class="btn btn-primary" value="Donate Now" /><br><br>
                </form>
            </div>
        </div> <br><br><br><br>

        <div class="fundraisers">

            <div id="fundraisercards">
                <h1 id="happen2">Fundraising Activities</h1><br>
                <?php
                if ($fundraisers) {
                    foreach ($fundraisers as $ROW) {
                        include("fundraisecard.php");
                    }
                }
                ?>

            </div>
        </div>

        <br><br><br><br>


        <div class="row" id="lingapbaste">
            <div class="baste">
                <div class="col-md-5" id="bastedetails">
                    <span>LINGAP BASTE</span>

                    <p>
                        It started as a simple response to the needs of the pandemic. But grew as a ministry in the Diocese of Tarlac to permanently address the need to be always prepared for anything that is worse. To have a Church of committed frontliners for calamities and disasters is its vision. While accommodating to help in any way for whoever Is greatly affected is its mission. Moreover, the ministry is geared to follow the call of Pope Francis' Encyclical; Laudato Si.
                        <br><br>
                        VISION
                        <br><br>
                        We envision a Church of God-centered, service-oriented and well-equipped first responders in times of crises and disasters.
                        <br><br>
                        MISSION
                        <br><br>
                        To heed the call of Pope Francis' Encyclical; Laudato Si. To care for our common home towards a more sustainable living by conducting agricultural projects.
                        Lingap Baste is named after the principal patron saint of the diocese, Saint Sebastian. We entrust our endeavors to his care and intercession. The organization was formally established on April 10 last year by His Excellency, the Most Rev. Enrique V. Macaraeg D.D., Bishop of Tarlac. We are currently on our first year of service and counting!
                    </p>
                </div>
                <div class="col-md-5" id="bastepic">
                    <img src="images/aboutlingap.jpg" alt="about lingap baste">
                </div>
            </div>
        </div>
    </div>
</body>

</html>