<?php
require_once 'Connect.php';

if(isset($_POST['id_cart'])) {
  $id_cart = $_POST['id_cart'];
  $delete = mysqli_query($conn, "DELETE FROM cart WHERE id_cart='$id_cart'");
  if($delete) {
    echo "Item deleted successfully!";
  } else {
    echo mysqli_error($conn);
  }
}
?>