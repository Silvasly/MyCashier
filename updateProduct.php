<?php
require 'Connect.php';

if(isset($_POST['ProductID']) && isset($_POST['ProductName']) && isset($_POST['date']) && isset($_POST['ProductPrice'])) {
    $ProductID = $_POST['ProductID'];
    $ProductName = $_POST['ProductName'];
    $Date = $_POST['date'];
    $ProductPrice = $_POST['ProductPrice'];
    $OldProductID = $_POST['OldProductID'];

    $query = "UPDATE `stock` SET `ProductID`='$ProductID', `ProductName`='$ProductName', `ProductPrice`='$ProductPrice', `Date`='$Date' WHERE `ProductID`='$OldProductID'";
    $record = mysqli_query($conn, $query);

    if($record) {
        echo 'success';
    } else {
        echo 'Error updating transaction.';
    }
}

?>