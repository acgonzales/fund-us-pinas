<?php

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["user_id"]) && isset($_POST["amount"])
) {
    require_once("classes/Donation.php");

    $donation = new Donation;

    $anonymous = isset($_POST["is_anonymous"]) ? $_POST["is_anonymous"] == "on" : false;
    $redirect = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "http://fundus.test/";

    $invoice = $donation->createFoundationDonation(
        $_POST["amount"],
        $_POST["user_id"],
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
