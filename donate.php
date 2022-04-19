<?php
session_start();

require_once("classes/User.php");
require_once("classes/Fundraiser.php");
require_once("classes/Donation.php");
require_once("classes/Shop.php");

$user = new User;
$fundraiser = new Fundraiser;
$donation = new Donation;
$shop = new Shop;

$user_data = null;

if (isset($_SESSION['fundus_userid'])) {
    $user_data = $user->getUserById($_SESSION['fundus_userid']);
    if (!$user_data) {
        unset($_SESSION["fundus_userid"]);
    }
}

$fundraiser_id = isset($_GET["fundraiser"]) ? $_GET["fundraiser"] : -1;
$fundraiserData = $fundraiser->getFundraiserById($fundraiser_id);

if (!$fundraiserData) {
    header("Location: login.php");
}

$donations = $donation->getFundraiserDonations($fundraiser_id);

$userShops = null;

if ($user_data) {
    $userShops = $shop->getShopsByUser($user_data["user_id"]);
}

$progressData = $donation->getFundraiserDonationProgress($fundraiser_id);
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

        <?php
        if ($user_data) {
            include("header.php");
        } else {
            include("login-header.php");
        }
        ?>

        <br><br><br>
        <div class="row" id="userline">
            <h1><?= $fundraiserData["title"] ?>
                <?php
                if ($user_data && $user_data["user_id"] == $fundraiserData["user_id"]) :
                ?>
                    <form action="/delete-fundraiser.php" method="POST" style="display: inline;">
                        <input type="hidden" name="fundraiser_id" value="<?= $fundraiser_id ?>" />
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                <?php endif ?>
            </h1><br><br>
            <div class="col-md-7" id="userimage">
                <img src="<?= $fundraiserData["image"] ?>" alt="user image fundraise">
            </div>
            <div class="col-md-5" id="moneyraised">
                <br><br>
                <strong>
                    P<?= $fundraiserData["goal_amount"] ?> goal
                </strong>

                <div class="progress" style="margin-top: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: <?= $progressData['progress'] ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <?= $progressData['progress'] ?>%
                    </div>
                </div>
                <hr><br>

                <strong>
                    Expires on <?= $fundraiserData["expiration_date"] ?>
                </strong><br><br>
                <hr><br>

                <span>
                    <?= count($donations) ?> donations
                </span><br>
                <hr>
                <?php
                foreach ($donations as $donation) {
                    $name = "Anonymous";

                    if ($donation["is_anonymous"] != 1) {
                        $name = $donation["donator_type"] == "App\\Models\\User" ? $donation["first_name"] . " " . $donation["last_name"] : $donation["shop_name"];
                    }

                    $amount = $donation["amount"];

                    echo "<span>";
                    echo "$name <br/>";
                    echo "P$amount";
                    echo "</span><br/><hr/>";
                }
                ?>
                <form action="/new-fundraiser-donation.php" method="POST">
                    <input type="hidden" name="fundraiser_id" value="<?= $fundraiser_id ?>" />
                    <input type="hidden" name="user_id" value="<?= $user_data ? $user_data["user_id"] : null ?>" />

                    <label for="donator_type"> Donate as: </label>
                    <input type="radio" name="donator_type" value="USER" checked> Myself
                    <input id="shop_option" type="radio" name="donator_type" value="SHOP" <?= !$userShops ? 'disabled' : '' ?>> Shop

                    <div id="shop_selection" style="display: none;">
                        <label for="shop_id">Shop:</label>
                        <select type="select" name="shop_id" id="shop_id">
                            <?php foreach ($userShops as $shop) : ?>
                                <option value="<?= $shop["shop_id"] ?>"><?= $shop["name"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <input name="amount" type="number" id="text" placeholder="Amount to Donate" required><br /><br />
                    <?php
                    if (!$user_data) :
                    ?>
                        <input type="hidden" name="is_anonymous" value="on" />
                    <?php else : ?>
                        <input type="checkbox" name="is_anonymous" /> Donate Anonymously? <br /><br />
                    <?php endif ?>

                    <input type="submit" id="donate" class="btn btn-primary" value="Donate Now" /><br><br>
                </form>
            </div>

        </div> <br><br>
        <div class="row" id="receiverdetails">
            <h1 id="organizer">
                Organizer: <?= $fundraiserData["first_name"] . " " . $fundraiserData["last_name"] ?>
            </h1>
            <p id="details">
                <?= $fundraiserData["description"] ?>
            </p>
        </div>
        <br><br><br><br>
    </div>
    <br><br>
    </div>
    <script>
        $("input[name='donator_type']").on("change checked", function(e) {
            if ($("#shop_option").is(":checked")) {
                $("#shop_selection").show();
            } else {
                $("#shop_selection").hide();
            }
        });
    </script>
</body>

</html>