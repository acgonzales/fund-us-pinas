<?php

session_start();

require_once("classes/User.php");

$user = new User;

$email = "";
$password = "";

if (isset($_SESSION["fundus_userid"])) {
    $user_data = $user->getUserById($_SESSION['fundus_userid']);
    if ($user_data) {
        header("Location: user.php");
    } else {
        unset($_SESSION["fundus_userid"]);
    }
}

?>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/userlogin.css">
    <!-- jQuery library -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">


        <div class="row" id="fundnav">
            <div class="col-md-8">
                <a href="index.php"><img src="images/logo.png" alt="logo of fund us pinas"></a>
            </div>

        </div>
        <div class="row" id="loginline">

            <div class="col-md-5" id="tag2">
                <h1 id="happen">We are waiting for you!</h1>

            </div>
            <div class="col-md-6" id="login">
                <div id="back">
                    <a href="index.php"><img src="images/back.png" alt="back button to home"></a><a href="index.php">Go back</a>
                </div>
                <br>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    try {
                        $success = $user->login($email, $password);

                        if (!$success) {
                            throw new Exception("Unexpected error.");
                        }

                        header("Location: user.php");
                        die;
                    } catch (Exception $e) {
                        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
                        echo $e->getMessage();
                        echo "</div>";
                    }
                }
                ?>
                <h3>Log into Fund us Pinas Community</h3><br><br>
                <form method="post">

                    <p>PLEASE ENTER YOUR EMAIL</p>
                    <input name="email" value="<?php echo $email ?>" type="text" id="text" placeholder="Email"><br><br><br>
                    <p>PLEASE ENTER YOUR PASSWORD</p>
                    <input name="password" value="<?php echo $password ?>" type="password" id="text" placeholder="Password"><br><br>
                    <input type="submit" id="button" value="Login"><br><br>
                    <a href="signup.php">No account? Sign up</a><br><br><br><br>

                </form>
            </div>
        </div>

    </div>
</body>

</html>