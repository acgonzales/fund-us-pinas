<div class="row" id="fundnav">
    <div class="col-md-8">
        <a href="user.php"><img id="Funduspinaslogo" src="images/logo.png" alt="logo of fund us pinas"></a>
    </div>
    <div class="col-md-4">
        <div class="row"><br><br><br>
            <?php

            $image = "images/male.png";
            if ($user_data['gender'] == "Female") {
                $image = "images/female.jpg";
            }


            ?>
            <a href="#">
                <img id="userimage3" src="<?= $image ?>" alt="user image">
                <!--<img id="userimage" src="jakey.jpg" alt="user image">-->
            </a>
            <a id="name" href="#"><?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?></a>
            <a id="navigate1" href="fundraiseregister.php">Start A Fundraise</a>
        </div>
        <div class="row">
            <ul class="nav navbar-nav">
                <li><a id="navigate2" href="user.php"><img src="images/homeicon.png" alt="home icon"></a></li>
                <li><a id="navigate2" href="story.php"><img src="images/storyicon.png" alt="success story icon"></a></li>
                <li><a id="navigate2" href="market.php"><img src="images/marketicon.png" alt="market icon"></a></li>
                <li><a id="navigate2" href="logout.php"><img src="images/logout.png" alt="logout icon"></a></li>
            </ul>
        </div>
    </div>
</div>