<?php  

require 'Connect.php';
session_start();

//--------------------------------- login ----------------------------
//This is for accepting the picture before login
if(!isset($_SESSION['usernameAdmin'])){
	echo "<script> alert('You need to login first before deleting') </script>";
	echo "<script> document.location.href = 'cashier.php' </script>";
}

$notrx = $_GET['NoTransaction'];

if ( delTrx($notrx) > 0 ) {
	echo "
		<script>
			alert('Delete Succeed!');
			window.location.href='homeAdmin.php';
		</script>
	";
}


?>