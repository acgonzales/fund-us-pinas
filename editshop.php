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

$name = $shopData["name"];
$description = $shopData["description"];
$fb = $shopData["fb_link"];
$ig = $shopData["ig_link"];
$shopee = $shopData["shopee_link"];
$lazada = $shopData["lazada_link"];

$error = null;

//for posting fundraisers in user page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $fb = $_POST["fb"];
        $ig = $_POST["ig"];
        $shopee = $_POST["shopee"];
        $lazada = $_POST["lazada"];
        $image = $shopData["image"];

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            $target_dir = "uploads/shop/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir);
            }

            $filename = basename($_FILES["image"]["name"]);

            $tmp = explode(".", $filename);
            $extension =  strtolower(end($tmp));

            $target_file = $target_dir . uniqid("story_") . "." . $extension;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                throw new Exception("Cannot upload file.");
            }

            $image = $target_file;
        }

        $success = $shop->updateShop($shopId, $name, $description, $image, $fb, $ig, $shopee, $lazada);

        if (!$success) {
            throw new Exception("Unexpected error.");
        }

        header("Location: market.php");
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

            <form method="post" enctype="multipart/form-data">
                <br><br>
                <p>PLEASE ENTER YOUR SHOP NAME</p>
                <input required value="<?php echo $name ?>" name="name" type="text" id="text" placeholder="Title"><br><br>
                <p>Enter your facebook link</p>
                <input value="<?php echo $fb ?>" name="fb" type="text" id="text" placeholder="facebook link"><br><br>
                <p>Enter your instagram link</p>
                <input value="<?php echo $ig ?>" name="ig" type="text" id="text" placeholder="Instagram link"><br><br>
                <p>Enter your shopee link</p>
                <input value="<?php echo $shopee ?>" name="shopee" type="text" id="text" placeholder="Shopee link"><br><br>
                <p>Enter your Lazada link</p>
                <input value="<?php echo $lazada ?>" name="lazada" type="text" id="text" placeholder="Lazada link"><br><br>
                <br>
                <div id="uploadimage">
                    <p id="insertimg">Please insert an image of your shop</p>
                    <input type="file" id="upload1" name="image" accept="image/*"><br><br>
                </div>

                <br>
                <p>Add description about your shop</p>
                <textarea required placeholder="About your shop and its products" name="description" id="posttext" cols="30" rows="20"><?php echo $description ?></textarea>
                <br><br><br>

                <input type="submit" id="button" value="Submit"><br><br>

            </form>
        </div>
        <br><br><br><br>
    </div>
</body>

</html>