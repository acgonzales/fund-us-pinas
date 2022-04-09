<?php


session_start();

    if (isset($_SESSION['fundus_userid'])) 
    {

        unset($_SESSION['fundus_userid']);

    }
    
    header("Location: login.php");
    die;
?>