<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["fundraiser_id"])) {
    require_once("classes/Fundraiser.php");

    (new Fundraiser)->deleteFundraiserById($_POST["fundraiser_id"]);
}

header("Location: user.php");
