<?php

session_start();

$_SESSION = ["usernameAdmin"];
session_destroy();
header("Location: cashier.php");

?>