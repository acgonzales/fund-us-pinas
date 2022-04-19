<?php

use Xendit\Xendit;


require_once("./vendor/autoload.php");

$api_key = "xnd_development_C4gEaX9NiccOzVDCphMNufaqqAHAMts1OQCAC8GZQsYHBU0eG7luX0E3uHaE";
Xendit::setApiKey($api_key);

require_once("classes/User.php");
require_once("classes/Fundraiser.php");
require_once("classes/Shop.php");

const FOUNDATION_DONATION = "Foundation";
const FUNDRAISER_DONATION = "Fundraiser";
const SHOP_DONATION = "Shop";

const DONATOR_MAP = [
    'USER' => 'App\\Models\\User',
    'SHOP' => 'App\\Models\\Shop'
];

class Donation extends Database
{
    private User $user;
    private Fundraiser $fundraiser;
    private Shop $shop;

    public function __construct()
    {
        parent::__construct();

        $this->user = new User;
        $this->fundraiser = new Fundraiser;
        $this->shop = new Shop;
    }

    public function getFundraiserDonations($fundraiserId, $limit = 3)
    {
        $statement = $this->connection->prepare("SELECT d.*, u.first_name, u.last_name, s.name AS shop_name, SUM(d.amount) AS total_amount
                                                 FROM donations d 
                                                 LEFT JOIN users u ON u.user_id = d.donator_id
                                                 LEFT JOIN shops s on s.shop_id = d.donator_id
                                                 INNER JOIN fundraisers f ON f.fundraiser_id = d.fundraiser_id
                                                 WHERE d.is_paid = 1 AND d.fundraiser_id = ? AND d.donation_type = 'Fundraiser'
                                                 GROUP BY d.donation_id
                                                 ORDER BY d.donated_at DESC
                                                 LIMIT ?");
        $statement->bind_param("ii", $fundraiserId, $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getFundraiserDonationProgress($fundraiserId)
    {
        $statement = $this->connection->prepare("SELECT f.goal_amount, SUM(d.amount) AS total_amount, (SUM(d.amount)/ f.goal_amount) * 100 AS progress 
                                                 FROM donations d
                                                 INNER JOIN fundraisers f ON f.fundraiser_id = d.fundraiser_id
                                                 WHERE d.is_paid = 1 AND d.donation_type = 'Fundraiser' AND d.fundraiser_id = ?
                                                 GROUP BY f.goal_amount");
        $statement->bind_param("i", $fundraiserId);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            return false;
        }

        $progress = $result->fetch_assoc();

        $statement->close();

        return $progress;
    }

    public function createFundraiserDonation($fundraiserId, $donator_type, $donator_id, $amount, $isAnonymous, $redirect_to = null)
    {
        $fundraiser = $this->fundraiser->getFundraiserById($fundraiserId);

        if (!$fundraiser) {
            throw new Exception("Fundraiser User not found.");
        }

        $payerEmail = "admin@funduspinas.co";

        if ($donator_type != null && $donator_id != null) {
            if ($donator_type == 'USER') {
                $user = $this->user->getUserById($donator_id);

                if (!$user) throw new Exception('User not found.');

                $payerEmail = $user["email"];
            } else if ($donator_type == 'SHOP') {
                $shop = $this->shop->getShopById($donator_id);

                if (!$shop) throw new Exception('Shop not found.');

                $payerEmail = $shop['email'];
            } else {
                throw new Exception('Invalid donator type.');
            }
        }

        $description = "Donation for " . $fundraiser["title"];

        $donationId = $this->createDonation($donator_type, $donator_id, $amount, $isAnonymous, $fundraiserId);
        $externalId = "donation_fundraiser_" . uniqid(); // ? Randomize, also store in db

        $invoice = $this->createXenditInvoice($externalId, $amount, $payerEmail, $description, success_redirect: $redirect_to);
        $this->updateDonationAddXenditId($donationId, $invoice["id"]);

        return $invoice;
    }

    public function getFoundationDonations($limit = 3)
    {
        $statement = $this->connection->prepare("SELECT d.*, u.first_name, u.last_name, s.name AS shop_name FROM donations d 
                                                 LEFT JOIN users u ON u.user_id = d.donator_id 
                                                 LEFT JOIN shops s on s.shop_id = d.donator_id
                                                 WHERE d.is_paid = 1 AND d.donation_type = 'Foundation'
                                                 ORDER BY d.donated_at DESC
                                                 LIMIT ?");
        $statement->bind_param("i", $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getFoundationTotalDonations()
    {
        return 0;
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

    public function createFoundationDonation($donator_type, $donator_id, $amount, $isAnonymous, $redirect_to = null)
    {
        $payerEmail = "admin@funduspinas.co";

        if ($donator_type != null) {
            if ($donator_type == 'USER') {
                $user = $this->user->getUserById($donator_id);

                if (!$user) throw new Exception('User not found.');

                $payerEmail = $user["email"];
            } else if ($donator_type == 'SHOP') {
                $shop = $this->shop->getShopById($donator_id);

                if (!$shop) throw new Exception('Shop not found.');

                $payerEmail = $shop['email'];
            } else {
                throw new Exception('Invalid donator type.');
            }
        }


        $description = "Donation for Lingap Baste Foundation";

        $donationId = $this->createDonation($donator_type, $donator_id, $amount, $isAnonymous);
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

    private function createDonation($donator_type, $donator_id, $amount, $isAnonymous, $fundraiserId = null)
    {
        $isAnonymousNumber = $isAnonymous ? 1 : 0;
        $donationType = $fundraiserId ? "Fundraiser" : "Foundation";
        $donatorType = DONATOR_MAP[$donator_type];

        $statement = $this->connection->prepare("INSERT INTO donations 
                                                (donator_type, donator_id, donation_type, fundraiser_id, amount, is_anonymous)
                                                VALUES (?, ?, ?, ?, ?, ?)");
        $statement->bind_param("sisidi", $donatorType, $donator_id, $donationType, $fundraiserId, $amount, $isAnonymousNumber);
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
