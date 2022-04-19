<?php

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["amount"])
) {
    require_once("classes/Donation.php");

    $donation = new Donation;

    $redirect = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "http://fundus.test/";

    $invoice = $donation->createFoundationDonation(null, null, $_POST["amount"], true, redirect_to: $redirect);
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
