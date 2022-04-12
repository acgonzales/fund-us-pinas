
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

        <div class="regform" id="registration">


            <h1>Fill out the form to register your shop</h1><br>

            <?php

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $error != null) {
                echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
                echo $error;
                echo "</div>";
            }
            ?>
            
            <form method="post">
                <br><br>
                <p>PLEASE ENTER YOUR SHOP NAME</p>
                <input value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="Title"><br><br>
                <p>Enter your facebook link</p>
                <input value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="facebook link"><br><br>
                <p>Enter your instagram link</p>
                <input value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="Instagram link"><br><br>
                <p>Enter your shopee link</p>
                <input value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="Shopee link"><br><br>
                <p>Enter your Lazada link</p>
                <input value="<?php echo $title ?>" name="title" type="text" id="text" placeholder="Lazada link"><br><br>
                <br>
                <div id="uploadimage">
                    <p id="insertimg">Please insert an image of your shop</p>
                    <input type="file" id="upload1" ><br><br>
                </div>
                
                <br>
                <p>Add description about your shop</p>
                <textarea value="<?php echo $description ?>" placeholder="About your shop and its products" name="description" id="posttext" cols="30" rows="20"></textarea>
                <br><br><br>
                
                <input type="submit" id="button" value="Submit"><br><br>

            </form>
        </div>
        <br><br><br><br>
    </div>
</body>

</html>