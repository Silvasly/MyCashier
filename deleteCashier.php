<?php  

require 'Connect.php';
session_start();
if(!isset($_SESSION['usernameAdmin'])){
	echo "<script> alert('You need to login first before deleting') </script>";
	echo "<script> document.location.href = 'admin.php' </script>";
}

$UsernameCashier = $_GET['Username'];

if ( delCashier($UsernameCashier) > 0 ) {
	echo "
		<script>
			alert('Delete Succeed!');
			window.location.href='createCashier.php';
		</script>
	";
}


?>