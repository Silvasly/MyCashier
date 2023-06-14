<?php 

require 'Connect.php';

session_start();

if(!isset($_SESSION['usernameAdmin'])){
    header("Location: admin.php");
}

$username = $_GET['usernameAdmin'];

$query = "SELECT `Namefile`, `Typefile`, `Size`, `Picture` FROM `admin` WHERE Username = '$username'";

$result = mysqli_query($conn,$query) or die('Error, query failed');

list($namefile, $type, $size, $content) = mysqli_fetch_array($result);

echo $content;
header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: attachment; filename= $namefile");


?>
