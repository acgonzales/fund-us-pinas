<?php

require_once("Database.php");

class Story extends Database
{
    public function getAllStories()
    {
        $statement = $this->connection->prepare("SELECT s.*, u.* FROM stories s 
                                                 INNER JOIN users u ON u.user_id = s.user_id 
                                                 ORDER BY s.created_at");
        $statement->execute();

        $results = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $results;
    }

    public function createStory($userId, $content, $image = null)
    {
        $statement = $this->connection->prepare("INSERT INTO stories 
                                                (user_id, content, image, created_at, updated_at)
                                                VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $statement->bind_param("iss", $userId, $content, $image);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    public function deleteStoryById($storyId)
    {
        $statement = $this->connection->prepare("DELETE FROM stories WHERE story_id = ?");
        $statement->bind_param("i", $storyId);

        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }

    public function likeStory($storyId)
    {
        $statement = $this->connection->prepare("UPDATE stories SET likes = likes + 1 WHERE story_id = ?");
        $statement->bind_param("i", $storyId);
        $success = $statement->execute();

        if (!$success) {
            throw new Exception($statement->error);
        }

        $statement->close();

        return true;
    }
}
