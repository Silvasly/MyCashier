<?php  

require 'Connect.php';
session_start();
if(!isset($_SESSION['usernameAdmin'])){
	echo "<script> alert('You need to login first before deleting') </script>";
	echo "<script> document.location.href = 'cashier.php' </script>";
}

$noProduct = $_GET['ProductID'];

if ( delProduct($noProduct) > 0 ) {
	echo "
		<script>
			alert('Delete Succeed!');
			window.location.href='createProduct.php';
		</script>
	";
}


?>