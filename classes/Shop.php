<?php

require_once("Database.php");

class Shop extends Database
{
    public function createShop($userId, $name, $description, $image = null, $fb = null, $ig = null, $shopee = null, $lazada = null)
    {
        $statement = $this->connection->prepare("INSERT INTO shops 
                                                (user_id, name, description, image, fb_link, ig_link, shopee_link, lazada_link)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->bind_param("isssssss", $userId, $name, $description, $image, $fb, $ig, $shopee, $lazada);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    public function updateShop($shopId, $name, $description, $image = null, $fb = null, $ig = null, $shopee = null, $lazada = null)
    {
        $statement = $this->connection->prepare("UPDATE shops 
                                                 SET name=?, description=?, image=?, fb_link=?, ig_link=?, shopee_link=?, lazada_link=?
                                                 WHERE shop_id = ?");
        $statement->bind_param("sssssssi", $name, $description, $image, $fb, $ig, $shopee, $lazada, $shopId);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    public function deleteShopById($shopId)
    {
        $statement = $this->connection->prepare("UPDATE shops SET deleted_at=CURRENT_TIMESTAMP WHERE shop_id = ?");
        $statement->bind_param("i", $shopId);

        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    public function getShopById($shopId)
    {
        $statement = $this->connection->prepare("SELECT s.*, u.* FROM shops s
                                                 INNER JOIN users u ON u.user_id = s.user_id
                                                 WHERE deleted_at IS NULL AND shop_id=? LIMIT 1");
        $statement->bind_param("i", $shopId);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows < 1) {
            return false;
        }

        $fundraiser = $result->fetch_assoc();

        $statement->close();

        return $fundraiser;
    }

    public function getShopsByUser($userId)
    {
        $statement = $this->connection->prepare("SELECT s.*, u.* FROM shops s
                                                 INNER JOIN users u ON u.user_id = s.user_id
                                                 WHERE deleted_at IS NULL AND s.user_id=?");
        $statement->bind_param("i", $userId);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getAllConfirmedShops($limit = 6)
    {
        $statement = $this->connection->prepare("SELECT s.*, u.* FROM shops s 
                                                 INNER JOIN users u ON u.user_id = s.user_id 
                                                 WHERE deleted_at IS NULL AND is_confirmed = 1
                                                 ORDER BY shop_id DESC
                                                 LIMIT ?");
        $statement->bind_param("i", $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function getAllShops($limit = 6)
    {
        $statement = $this->connection->prepare("SELECT s.*, u.* FROM shops s 
                                                 INNER JOIN users u ON u.user_id = s.user_id 
                                                 WHERE deleted_at IS NULL
                                                 ORDER BY shop_id DESC
                                                 LIMIT ?");
        $statement->bind_param("i", $limit);
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }
}
