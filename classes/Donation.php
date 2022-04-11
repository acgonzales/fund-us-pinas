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
        $statement = $this->connection->prepare("SELECT d.*, u.* FROM donations d 
                                                 INNER JOIN users u ON u.user_id = d.donatable_id 
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

    public function createFundraiserDonation($fundraiserId, $userId, $amount, $isAnonymous)
    {
        $fundraiser = $this->fundraiser->getFundraiserById($fundraiserId);
        $user = $this->user->getUserById($userId);

        if (!$fundraiser || !$user) {
            throw new Exception("Fundraiser or User not found.");
        }

        $payerEmail = $user["email"];
        $description = "Donation for " . $fundraiser["title"];

        $donationId = $this->createDonation($userId, FUNDRAISER_DONATION, $amount, $isAnonymous, $fundraiserId);
        $externalId = "1_donation_fundraiser_" . $donationId; // ? Randomize, also store in db

        $invoice = $this->createXenditInvoice($externalId, $amount, $payerEmail, $description);

        $this->updateDonationAddXenditId($donationId, $invoice["id"]);

        return $invoice;
    }

    public function getDonationByXenditId($xenditId)
    {
        $statement = $this->connection->prepare("SELECT * FROM donations 
                                                 WHERE xendit_id=? LIMIT 1");
        $statement->bind_param("i", $xenditId);
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

    private function createXenditInvoice($externalId, $amount, $payerEmail = "donations@funduspinas.co", $description = "Fundraiser Donation")
    {
        $invoice_params = [
            "external_id" => $externalId,
            "payer_email" => $payerEmail,
            "description" => $description,
            "amount" => $amount
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
