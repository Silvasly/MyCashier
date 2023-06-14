<?php
require 'Connect.php';

if(isset($_POST['noTransaction']) && isset($_POST['cashier']) && isset($_POST['date']) && isset($_POST['total'])) {
    $noTransaction = $_POST['noTransaction'];
    $cashier = $_POST['cashier'];
    $date = $_POST['date'];
    $total = $_POST['total'];

    $query = "UPDATE `transaction` SET `Cashier`='$cashier', `Date`='$date', `Total`='$total' WHERE `NoTransaction`='$noTransaction'";
    $record = mysqli_query($conn, $query);

    if($record) {
        echo 'success';
    } else {
        echo 'Error updating transaction.';
    }
}

?>