<?php
require_once 'Connect.php';

// Get transaction details
$notrx = $_GET['notrx'];
$total = $_GET['total'];

$cart = mysqli_query($conn, "SELECT * FROM cart WHERE username = '$usernameCashier'");

// Create receipt HTML
$html = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .mt-4 {
      margin-top: 4rem;
    }
    .mb-3 {
      margin-bottom: 3rem;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <div class="row mb-3">
      <div class="col-md-6">
        <span>Date: '.date('d F Y').'</span><br>
        <span>Time: '.date('H:i:s').'</span>
      </div>
      <div class="col-md-6 text-right">
        <span>Invoice No: '.$notrx.'</span>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>No.</th>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>';
$i = 1;
foreach($cart as $cr) {
  $html .= '<tr>
    <td>'.$i.'</td>
    <td>'.$cr['product_name'].'</td>
    <td>'.$cr['quantity'].'</td>
    <td>Rp. '.number_format($cr['price']).'</td>
    <td>Rp. '.number_format($cr['total']).'</td>
  </tr>';
  $i++;
}
$html .= '</tbody>
    </table>
    <div class="text-right mt-3">
      <p>Subtotal: Rp. '.number_format($subtotal).'</p>
      <p>Tax (5%): Rp. '.number_format($tax).'</p>
      <p><strong>Gross Total: Rp. '.number_format($total).'</strong></p>
    </div>
  </div>
</body>
</html>';

// Generate PDF file
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Receipt.pdf');
?>