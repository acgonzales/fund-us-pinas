<?php

require_once("Database.php");

class Fundraiser extends Database
{
    public function getFundraiserById($fundraiserId)
    {
        $statement = $this->connection->prepare("SELECT f.*, u.* FROM fundraisers f
                                                 INNER JOIN users u ON u.user_id = f.user_id
                                                 WHERE fundraiser_id=? LIMIT 1");
        $statement->bind_param("i", $fundraiserId);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            return false;
        }

        $fundraiser = $result->fetch_assoc();

        $statement->close();

        return $fundraiser;
    }

    public function deleteFundraiserById($fundraiserId)
    {
        $statement = $this->connection->prepare("UPDATE fundraisers SET deleted_at=CURRENT_TIMESTAMP WHERE fundraiser_id = ?");
        $statement->bind_param("i", $fundraiserId);

        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    public function getNotExpiredFundraisers($limit = 5)
    {
        $statement = $this->connection->prepare("SELECT f.*, u.* FROM fundraisers f 
                                                 INNER JOIN users u ON u.user_id = f.user_id 
                                                 WHERE deleted_at IS NULL AND expiration_date > CURDATE()
                                                 ORDER BY fundraiser_id LIMIT ?");
        $statement->bind_param("i", $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getFundraisers($limit = 5)
    {
        $statement = $this->connection->prepare("SELECT f.*, u.* FROM fundraisers f 
                                                 INNER JOIN users u ON u.user_id = f.user_id 
                                                 WHERE deleted_at IS NULL
                                                 ORDER BY fundraiser_id LIMIT ?");
        $statement->bind_param("i", $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getFundraisersByUser($userId, $limit = 5)
    {
        $statement = $this->connection->prepare("SELECT f.*, u.* FROM fundraisers f 
                                                 INNER JOIN users u ON u.user_id = f.user_id 
                                                 WHERE deleted_at IS NULL AND f.user_id = ? AND expiration_date > CURDATE()
                                                 ORDER BY fundraiser_id LIMIT ?");
        $statement->bind_param("ii", $userId, $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function createFundraiser($userId, $title, $description, $goalAmount, $expirationDate, $gcash, $gcashAccount, $image = null)
    {
        $statement = $this->connection->prepare("INSERT INTO fundraisers 
                                                (user_id, title, image, description, goal_amount, expiration_date, gcash, gcash_account, created_at, updated_at)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $statement->bind_param("isssssss", $userId, $title, $image, $description, $goalAmount, $expirationDate, $gcash, $gcashAccount);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }
}
