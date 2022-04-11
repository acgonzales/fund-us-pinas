<?php
session_start();

require_once("classes/User.php");
require_once("classes/Fundraiser.php");
require_once("classes/Donation.php");

$user = new User;
$fundraiser = new Fundraiser;
$donation = new Donation;

$fundraiser_id = isset($_GET["fundraiser"]) ? $_GET["fundraiser"] : -1;
$fundraiserData = $fundraiser->getFundraiserById($fundraiser_id);

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

if (!$fundraiserData) {
    header("Location: login.php");
}

$donations = $donation->getFundraiserDonations($fundraiser_id);
//for posting success stories
// if ($_SERVER['REQUEST_METHOD'] == "POST") {

//     $post = new Post();
//     $id = $_SESSION['fundus_userid'];
//     $result = $post->create_post($id, $_POST);

//     if ($result == "") {
//         header("Location: story.php");
//         die;
//     } else {
//         echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
//         echo $result;
//         echo "</div>";
//     }
// }

// //collecting all posts
// $post = new Post();
// $id = $_SESSION['fundus_userid'];
// $posts = $post->get_posts($id);

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
            <h1><?= $fundraiserData["title"] ?></h1><br><br>
            <div class="col-md-7" id="userimage">
                <img src="images/sick.png" alt="user image fundraise">
            </div>
            <div class="col-md-5" id="moneyraised">
                <br><br>
                <strong>
                    P<?= $fundraiserData["goal_amount"] ?> goal
                </strong><br><br>
                <hr><br>

                <span>
                    <?= count($donations) ?> donations
                </span><br>
                <hr>
                <?php
                foreach ($donations as $donation) {
                    $name = $donation["is_anonymous"] == 1 ? "Anonymous" : $donation["first_name"] . " " . $donation["last_name"];
                    $amount = $donation["amount"];

                    echo "<span>";
                    echo "$name <br/>";
                    echo "P$amount";
                    echo "</span><br/><hr/>";
                }
                ?>
                <form action="/new-fundraiser-donation.php" method="POST">
                    <input type="hidden" name="fundraiser_id" value="<?= $fundraiser_id ?>" />
                    <input type="hidden" name="user_id" value="<?= $user_data["user_id"] ?>" />
                    <input name="amount" type="number" id="text" placeholder="Amount to Donate" required><br /><br />
                    <input type="checkbox" name="is_anonymous" /> Donate Anonymously? <br /><br />
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
</body>

</html>