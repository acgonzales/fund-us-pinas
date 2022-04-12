<?php

require_once("classes/Donation.php");

$donationCls = new Donation;

try {
    $json = file_get_contents('php://input');

    // Converts it into a PHP object
    $data = json_decode($json, true);
    $invoiceId = isset($data["id"]) ? $data["id"] : -1;

    $donation = $donationCls->getDonationByXenditId($invoiceId);

    if (!$donation) {
        throw new Exception("Donation does not exist.");
    }

    $donationCls->markDonationAsPaid($donation["donation_id"]);

    echo "OK";
} catch (Exception $e) {
    echo $e->getMessage();
    // http_response_code(500);
}
