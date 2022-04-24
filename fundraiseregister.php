<?php

session_start();

require_once("classes/User.php");
require_once("classes/Fundraiser.php");

$user = new User;
$fundraiser = new Fundraiser;

if (empty($_SESSION["fundus_userid"])) {
    header("Location: login.php");
}

$user_data = $user->getUserById($_SESSION['fundus_userid']);

if (!$user_data) {
    header("Location: login.php");
    die;
}

$title = "";
$amountgoal = "";
$description = "";
$expirationDate = "";

$error = null;



//for posting fundraisers in user page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $goalAmount = $_POST["amountgoal"];
    $expirationDate = $_POST["expirationDate"];
    $gcash = $_POST["gcash"];
    $image = null;

    try {
        if (isset($_FILES["image"])) {
            $target_dir = "uploads/fundraiser/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }

            $filename = basename($_FILES["image"]["name"]);

            $tmp = explode(".", $filename);
            $extension =  strtolower(end($tmp));

            $target_file = $target_dir . uniqid("fundraiser_") . "." . $extension;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                throw new Exception("Cannot upload file.");
            }

            $image = $target_file;
        }

        $success = $fundraiser->createFundraiser(
            $user_data["user_id"],
            $title,
            $description,
            $goalAmount,
            $expirationDate,
            $gcash,
            $image
        );

        if (!$success) {
            throw new Exception("Unexpected error.");
        }

        header("Location: user.php");
        die;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<html>

<head>
    <title>Success Story Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stories.css">
    <link rel="stylesheet" href="css/fundraiseform.css">
    <!-- jQuery library -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">

        <?php include("header.php"); ?>

        <div class="regform" id="registration"><br><br>


            <h1>Fill out the form to start the fundraising</h1><br>

            <?php

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $error != null) {
                echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
                echo $error;
                echo "</div>";
            }
            ?>
            <form method="post" enctype="multipart/form-data">
                <?php
                $date = new DateTime;
                $date->add(new DateInterval('P30D'));
                ?>
                <input required value="<?= $date->format('Y-m-d') ?>" name="expirationDate" type="hidden" id="text" placeholder="Expiration date" />

                <br><br>
                <p>PLEASE ENTER THE REASON OF THE FUNDRAISING</p>
                <input required value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="Title"><br><br>
                <p>PLEASE ENTER YOUR GCASH NUMBER</p>
                <input required value="<?php echo $gcash ?>" name="gcash" type="number" id="text" placeholder="Gcash"><br><br>
                <p>PLEASE ENTER THE GOAL AMOUNT</p>
                <input required value="<?php echo $amountgoal ?>" name="amountgoal" type="number" min="500" id="text" placeholder="Goal amount"><br><br><br>
                <p>Please insert an image of your fundraising activity</p>
                <input required type="file" id="upload" name="image" accept="image/*"><br><br>
                <p>PLEASE ADD DESCRIPTION ABOUT THE FUNDRAISING</p>

                <textarea required value="<?php echo $description ?>" placeholder="About the fundraising" name="description" id="posttext" cols="30" rows="20"></textarea>

                <br><br>
                <input type="submit" id="button" value="Submit"><br><br>

            </form>
        </div>
        <br><br><br><br>
    </div>
</body>

</html>