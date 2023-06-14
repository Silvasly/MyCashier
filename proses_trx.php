<?php  

require 'Connect.php';

if ( addtrx($_POST) > 0 ) {
	echo "
	<script>
		alert('Success Pay Transaction!');
		window.location.href='homeCashier.php';
	</script>
	";
	$us = $_POST['username'];
	mysqli_query($conn, "DELETE FROM cart WHERE username = '$us'");

} else {
	mysqli_error($conn);
	
}

?>