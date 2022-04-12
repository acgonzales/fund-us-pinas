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
                if ($user_data && $user_data["user_id"] == $ROW["user_id"]) :
                ?>
                    <form action="/delete-story.php" method="POST" style="display: inline;">
                        <input type="hidden" name="story_id" value="<?= $ROW["story_id"] ?>" />
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                <?php endif ?>
            </div><br>
            <!-- POST TEXT-->

            <p id="posting">
                <?php echo  $ROW['content'] ?>
                <?php if (!empty($ROW["image"])) : ?>
                    <br />
                    <img src="<?= $ROW["image"] ?>" style="height: 150px; width: 300px;" />
                <?php endif ?>
            </p>

            <br>
            <form action="/like.php" method="POST" id="like_form">
                <input type="hidden" name="story_id" value="<?= $ROW["story_id"] ?>" />
                <button id="like" type="submit" style="border: none;"><a>Like</a></button>
                ~
                <span style="color: #999;">
                    <?php
                    echo $ROW['likes']
                    ?>
                    likes
                </span>
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