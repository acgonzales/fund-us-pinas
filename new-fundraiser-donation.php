<?php

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["fundraiser_id"])
    && isset($_POST["user_id"]) && isset($_POST["amount"])
) {
    require_once("classes/Donation.php");

    $anonymous = isset($_POST["is_anonymous"]) ? $_POST["is_anonymous"] == "on" : false;

    $donation = new Donation;

    $invoice = $donation->createFundraiserDonation(
        $_POST["fundraiser_id"],
        $_POST["user_id"],
        $_POST["amount"],
        $anonymous
    );
    $checkoutUrl = $invoice["invoice_url"];

    header("Location: $checkoutUrl");
    die;
} else {
    header("Location: user.php");
    die;
}
