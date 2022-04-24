<div id="fundraise" class="card" style="width: 27%;">

    <img class="card-img-top" src="<?= $ROW["image"] ?>" alt="Card image cap" style="height: 60%; width: 100%;">

    <div class="card-body">
        <h5 class="card-title" style="font-weight: bolder; font-size: 15px;  text-align: center;">
            <?php
            echo $ROW['title'];
            ?>
        </h5>
        <h6 class="card-subtitle mb-2 text-center">
            <?= $ROW["first_name"] . " " . $ROW["last_name"] ?>
        </h6>

        <h5 style="font-weight: bolder;">
            <br>Goal Amount:
            <?php
            echo $ROW['goal_amount'];
            ?>
        </h5>

        <a href="donate.php?fundraiser=<?= $ROW["fundraiser_id"] ?>" class="btn btn-primary" id="cardbutton">See more</a>

    </div>
</div>