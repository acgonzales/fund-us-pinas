<div id="post_bar">
    <!-- post start -->
    <div id="post">
        <?php
        $image = "images/male.png";
        if ($ROW['gender'] == "Female") {
            $image = "images/female.jpg";
        }
        ?>
        <div>
            <img id="userimage2" src="<?php echo $image ?>" alt="user image">
        </div>
        <div>

            <div id="Username">
                <?php
                echo $ROW['first_name'] . " " . $ROW['last_name'];
                ?>
            </div><br>
            <!-- POST TEXT-->

            <p id="posting">
                <?php echo  $ROW['content'] ?>
            </p>

            <br><br>
            <form action="/like.php" method="POST" id="like_form">
                <input type="hidden" name="story_id" value="<?= $ROW["story_id"] ?>" />
                <a id="like" href="javascript:void(0)" onclick="document.getElementById('like_form').submit()">Like</a>
                ~
                <span style="color: #999;">
                    <?php
                    echo $ROW['created_at']
                    ?>
                </span>
            </form>
        </div>
    </div><br>
    <!-- post end -->
</div>