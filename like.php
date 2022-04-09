<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["story_id"])) {
    require_once("classes/Story.php");
    (new Story)->likeStory($_POST["story_id"]);
}

header("Location: story.php");
