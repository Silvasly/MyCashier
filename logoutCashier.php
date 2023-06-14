<?php

session_start();

$_SESSION = ["usernameCashier"];
session_destroy();
header("Location: cashier.php");

?>