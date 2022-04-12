<?php

use Xendit\Xendit;


require_once("./vendor/autoload.php");

$api_key = "xnd_development_C4gEaX9NiccOzVDCphMNufaqqAHAMts1OQCAC8GZQsYHBU0eG7luX0E3uHaE";
Xendit::setApiKey($api_key);

require_once("classes/User.php");
require_once("classes/Fundraiser.php");

const FOUNDATION_DONATION = "Foundation";
const FUNDRAISER_DONATION = "Fundraiser";
const SHOP_DONATION = "Shop";

class Donation extends Database
{
    private User $user;
    private Fundraiser $fundraiser;

    public function __construct()
    {
        parent::__construct();

        $this->user = new User;
        $this->fundraiser = new Fundraiser;
    }

    public function getFundraiserDonations($fundraiserId, $limit = 3)
    {
        $donationType = FUNDRAISER_DONATION;
        $statement = $this->connection->prepare("SELECT d.*, u.*, SUM(d.amount) as total_amount FROM donations d 
                                                 LEFT JOIN users u ON u.user_id = d.donatable_id 
                                                 INNER JOIN fundraisers f ON f.fundraiser_id = d.fundraiser_id
                                                 WHERE d.is_paid = 1 AND d.fundraiser_id = ? AND d.donatable_type = ?
                                                 ORDER BY d.donated_at DESC
                                                 LIMIT ?");
        $statement->bind_param("isi", $fundraiserId, $donationType, $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function createFundraiserDonation($fundraiserId, $userId, $amount, $isAnonymous, $redirect_to = null)
    {
        $fundraiser = $this->fundraiser->getFundraiserById($fundraiserId);
        $user = $this->user->getUserById($userId);

        if (!$fundraiser) {
            throw new Exception("Fundraiser or User not found.");
        }

        $payerEmail = "admin@funduspinas.co";

        if ($user) {
            $payerEmail = $user["email"];
        }

        $description = "Donation for " . $fundraiser["title"];

        $donationId = $this->createDonation($userId, FUNDRAISER_DONATION, $amount, $isAnonymous, $fundraiserId);
        $externalId = "donation_fundraiser_" . uniqid(); // ? Randomize, also store in db

        $invoice = $this->createXenditInvoice($externalId, $amount, $payerEmail, $description, success_redirect: $redirect_to);

        $this->updateDonationAddXenditId($donationId, $invoice["id"]);

        return $invoice;
    }

    public function getFoundationDonations($limit = 3)
    {
        $donationType = FOUNDATION_DONATION;
        $statement = $this->connection->prepare("SELECT d.*, u.* FROM donations d 
                                                 LEFT JOIN users u ON u.user_id = d.donatable_id 
                                                 WHERE d.is_paid = 1 AND d.donatable_type = ?
                                                 ORDER BY d.donated_at DESC
                                                 LIMIT ?");
        $statement->bind_param("si", $donationType, $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getFoundationTotalDonations()
    {
        $donationType = FOUNDATION_DONATION;
        $statement = $this->connection->prepare("SELECT SUM(amount) AS total_amount FROM donations
                                                 WHERE donatable_type = ? AND is_paid = 1;");
        $statement->bind_param("s", $donationType);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            return 0;
        }

        $total = $result->fetch_assoc();

        $statement->close();

        return $total["total_amount"];
    }

    public function createFoundationDonation($amount, $userId = null, $isAnonymous = true, $redirect_to = null)
    {
        $payerEmail = "admin@funduspinas.co";

        if ($userId) {
            $user = $this->user->getUserById($userId);
            if (!$user) {
                throw new Exception("User not found.");
            }

            $payerEmail = $user["email"];
        }

        $description = "Donation for Lingap Baste Foundation";

        $donationId = $this->createDonation($userId, FOUNDATION_DONATION, $amount, $isAnonymous);
        $externalId = "donation_foundation_" . uniqid();

        $invoice = $this->createXenditInvoice($externalId, $amount, $payerEmail, $description, success_redirect: $redirect_to);

        $this->updateDonationAddXenditId($donationId, $invoice["id"]);

        return $invoice;
    }

    public function getDonationByXenditId($xenditId)
    {
        $statement = $this->connection->prepare("SELECT * FROM donations 
                                                 WHERE xendit_id=? LIMIT 1");
        $statement->bind_param("s", $xenditId);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            return false;
        }

        $user = $result->fetch_assoc();

        $statement->close();

        return $user;
    }

    public function markDonationAsPaid($donationId)
    {
        $statement = $this->connection->prepare("UPDATE donations SET is_paid = 1, donated_at = CURRENT_TIMESTAMP 
                                                 WHERE donation_id = ?");
        $statement->bind_param("i", $donationId);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    private function createXenditInvoice($externalId, $amount, $payerEmail = "donations@funduspinas.co", $description = "Fundraiser Donation", $success_redirect = null)
    {
        $invoice_params = [
            "external_id" => $externalId,
            "payer_email" => $payerEmail,
            "description" => $description,
            "amount" => $amount,
            "success_redirect_url" => $success_redirect
        ];
        return \Xendit\Invoice::create($invoice_params);
    }

    private function createDonation($donatableId, $donatableType, $amount, $isAnonymous, $fundraiserId = null)
    {
        $isAnonymousNumber = $isAnonymous ? 1 : 0;
        $statement = $this->connection->prepare("INSERT INTO donations 
                                                (donatable_id, donatable_type, amount, is_anonymous, fundraiser_id)
                                                VALUES (?, ?, ?, ?, ?)");
        $statement->bind_param("isdii", $donatableId, $donatableType, $amount, $isAnonymousNumber, $fundraiserId);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $id = $statement->insert_id;
        $statement->close();

        return $id;
    }

    private function updateDonationAddXenditId($donationId, $xenditId)
    {
        $statement = $this->connection->prepare("UPDATE donations SET xendit_id = ? WHERE donation_id = ?");
        $statement->bind_param("si", $xenditId, $donationId);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }
}
