<?php

    $host = "localhost";
    $dbname = "MyCashier";
    $user = "root";
    $password_user = "";
    $conn = mysqli_connect($host,$user,$password_user,$dbname) or die ("Couldn't connect to Server\n");

// _________________________ Function for delete __________________________



function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	};
	return $rows;
};

function addtocart($data){
	global $conn;

	$product_name = mysqli_real_escape_string($conn, $data["vegetable"]);
	$username = mysqli_real_escape_string($conn, $data["username"]);
	$quantity = mysqli_real_escape_string($conn, $data["quantity"]);

	$data = query("SELECT * FROM stock WHERE ProductName = '$product_name'")[0]; 
	$price = $data['ProductPrice'];

	$total = $price * $quantity;
                              

	mysqli_query($conn, "INSERT INTO cart 
		VALUES(NULL, '$username', '$product_name', '$quantity', '$price', '$total') ");

	return mysqli_affected_rows($conn);
}

function addtrx($data){
	global $conn;

	$notrx = mysqli_real_escape_string($conn, $data["notrx"]);
	$username = mysqli_real_escape_string($conn, $data["username"]);
	$total = mysqli_real_escape_string($conn, $data["total"]);
                              
	mysqli_query($conn, "INSERT INTO transaction 
		VALUES('$notrx', '$total', '$username', NULL) ");

	return mysqli_affected_rows($conn);
}

function delTrx($notrx) {
	global $conn;
	mysqli_query($conn, "DELETE FROM transaction WHERE NoTransaction = '$notrx'");

	return mysqli_affected_rows($conn);
}

function delProduct($noProduct) {
	global $conn;
	mysqli_query($conn, "DELETE FROM stock WHERE ProductID = '$noProduct'");

	return mysqli_affected_rows($conn);
}

function delAdmin($UsernameAdmin) {
	global $conn;
	mysqli_query($conn, "DELETE FROM admin WHERE Username = '$UsernameAdmin'");
	mysqli_query($conn, "DELETE FROM admininfo WHERE Username = '$UsernameAdmin'");
	return mysqli_affected_rows($conn);
}

function delCashier($UsernameCashier) {
	global $conn;
	mysqli_query($conn, "DELETE FROM cashier WHERE Username = '$UsernameCashier'");
	mysqli_query($conn, "DELETE FROM cashierinfo WHERE Username = '$UsernameCashier'");
	return mysqli_affected_rows($conn);
}






?>