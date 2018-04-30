<?php require_once("Include/DB.php") ?>
<?php require_once("Include/Session.php") ?>
<?php require_once("Include/Functions.php") ?>
<?php

$_SESSION['UserId']=null;
session_destroy();
redirect_to("Login.php");

?>