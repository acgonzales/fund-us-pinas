<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["shop_id"])) {
    require_once("classes/Shop.php");

    (new Shop)->deleteShopById($_POST["shop_id"]);
}

header("Location: market.php");
