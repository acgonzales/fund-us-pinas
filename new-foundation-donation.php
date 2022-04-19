<?php

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["user_id"]) && isset($_POST["amount"]) && isset($_POST["donator_type"])
) {
    require_once("classes/Donation.php");

    $donation = new Donation;

    $anonymous = isset($_POST["is_anonymous"]) ? $_POST["is_anonymous"] == "on" : false;

    $userId = !empty($_POST["user_id"]) ? $_POST["user_id"] : null;
    $shopId = !empty($_POST["shop_id"]) ? $_POST["shop_id"] : null;

    $donator_type = $_POST["donator_type"];
    $donator_id = $donator_type == "USER" ? $userId : $shopId;

    if ($donator_id == null) {
        $donator_type = null;
        $donator_id = null;
    }

    $redirect = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "http://fundus.test/";

    $invoice = $donation->createFoundationDonation(
        $donator_type,
        $donator_id,
        $_POST["amount"],
        $anonymous,
        redirect_to: $redirect
    );
    $checkoutUrl = $invoice["invoice_url"];

    header("Location: $checkoutUrl");
    die;
} else {
    $location = "Location: index.php";

    if (isset($_SERVER["HTTP_REFERER"])) {
        $location = "Location: " . $_SERVER["HTTP_REFERER"];
    }

    header($location);
    die;
}
