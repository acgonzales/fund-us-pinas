<?php

session_start();

require_once("classes/User.php");
require_once("classes/Story.php");

$user = new User;
$story = new Story;

if (empty($_SESSION["fundus_userid"])) {
    header("Location: login.php");
}

$user_data = $user->getUserById($_SESSION['fundus_userid']);

if (!$user_data) {
    header("Location: login.php");
}

//for posting success stories
$post_error = null;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $content = $_POST["post"];
        $result = $story->createStory($user_data["user_id"], $content);
        header("Location: story.php");
    } catch (Exception $e) {
        $post_error = $e->getMessage();
    }
}

$posts = $story->getAllStories();

?>
<html>

<head>
    <title>Success Story Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stories.css">
    <!-- jQuery library -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">

        <?php include("header.php"); ?>

        <div class="row" id="storyline"><br><br><br>
            <div id="tag2">
                <h1 id="happen">Post your success story!</h1>
                <h2 id="strong">Tell us about your appreciation</h2>
            </div>

        </div><br><br><br><br><br><br>

        <!-- post text area-->
        <form method="post">
            <?php
            if ($post_error != null) {
                echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
                echo $post_error;
                echo "</div>";
            }
            ?>
            <div class="row" id="successpost">
                <textarea placeholder="Thank the community" name="post" id="posttext" cols="30" rows="10"></textarea>
                <br><br>
                <input id="post_button" type="submit" value="Submit">
            </div><br><br><br>
        </form>
        <!-- POSTING AREA -->
        <?php
        if ($posts) {
            foreach ($posts as $ROW) {
                include("post.php");
            }
        }
        ?>

        <br><br><br><br>
    </div>
</body>

</html>