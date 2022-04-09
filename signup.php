<?php
require_once("classes/User.php");

$user = new User;

$first_name = null;
$last_name = null;
$gender = null;
$email = null;
?>

<html>

<head>
    <title>Signup Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/usersignup.css">
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
        <div class="row" id="signupline">

            <div class="col-md-5" id="tag2">
                <h1 id="happen">Join us in helping and giving hope to everyone!</h1>

            </div>
            <div class="col-md-6" id="signup">
                <div id="back">
                    <a href="index.php"><img src="images/back.png" alt="back button to home"></a><a href="index.php">Go back</a>
                </div>
                <br>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $first_name = $_POST['first_name'];
                    $last_name = $_POST['last_name'];
                    $gender = $_POST['gender'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $password_confirmation = $_POST['password2'];

                    try {
                        $success = $user->signUp(
                            $first_name,
                            $last_name,
                            $email,
                            $password,
                            $password_confirmation,
                            $gender
                        );

                        header("Location: login.php");
                        die;
                    } catch (Exception $e) {
                        echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
                        echo $e->getMessage();
                        echo "</div>";
                    }
                }
                ?>
                <h3>Sign up to Fund us Pinas Community</h3><br>

                <form method="post">

                    <p>PLEASE ENTER YOUR FIRST NAME</p>
                    <input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First Name"><br><br>
                    <p>PLEASE ENTER YOUR LAST NAME</p>
                    <input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last Name"><br><br><br>
                    <p>PLEASE ENTER YOUR EMAIL</p>
                    <input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email"><br><br>
                    <p>PLEASE ENTER YOUR PASSWORD</p>
                    <input name="password" type="password" id="text" placeholder="Password"><br><br>
                    <p>PLEASE RE-ENTER YOUR PASSWORD</p>
                    <input name="password2" type="password" id="text" placeholder="Re-enter Password"><br><br>
                    <p>PLEASE SELECT YOUR GENDER</p><br>
                    <select name="gender" id="text">

                        <option><?php echo $gender ?></option>
                        <option>Male</option>
                        <option>Female</option>

                    </select><br><br>
                    <input type="submit" id="button" value="Signup"><br><br>
                    <a href="login.php">Already have an account? Login</a><br><br><br><br>

                </form>
            </div>
        </div>
        <br><br><br><br><br>
    </div>
</body>

</html>