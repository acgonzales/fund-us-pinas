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

$title = "";
$amountgoal = "";
$description = "";

$error = null;

//for posting fundraisers in user page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $goalAmount = $_POST["amountgoal"];


    try {
        $success = $fundraiser->createFundraiser($user_data["user_id"], $title, $description, $goalAmount);

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
            <form method="post">
                <br><br>
                <p>PLEASE ENTER THE REASON OF THE FUNDRAISING</p>
                <input value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="Title"><br><br>
                <p>PLEASE ENTER THE GOAL AMOUNT</p>
                <input value="<?php echo $amountgoal ?>" name="amountgoal" type="number" id="text" placeholder="Goal amount"><br><br><br>
                <p>PLEASE ADD DESCRIPTION ABOUT THE FUNDRAISING</p>
                <textarea value="<?php echo $description ?>" placeholder="About the fundraising" name="description" id="posttext" cols="30" rows="20"></textarea>
                <br><br><br>
                <p>INPUT IMAGE</p>
                WALA PA
                <br><br>
                <input type="submit" id="button" value="Submit"><br><br>

            </form>
        </div>
        <br><br><br><br>
    </div>
</body>

</html>