<?php

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["fundraiser_id"])
    && isset($_POST["user_id"]) && isset($_POST["amount"])
) {
    require_once("classes/Donation.php");

    $anonymous = isset($_POST["is_anonymous"]) ? $_POST["is_anonymous"] == "on" : false;

    $donation = new Donation;

    $userId = !empty($_POST["user_id"]) ? $_POST["user_id"] : null;

    $redirect = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "http://fundus.test/";

    $invoice = $donation->createFundraiserDonation(
        $_POST["fundraiser_id"],
        $userId,
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
