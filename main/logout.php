<?php
    include_once("../helpers/api.php");
    unset($currentUser);
    session_destroy();
    header("Location: index.php");
    exit();
?>