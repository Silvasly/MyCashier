<?php

use LDAP\Result;

require 'Connect.php';
session_start();

//--------------------------------- login ----------------------------
//This is for accepting the picture before login
if(!isset($_SESSION['usernameAdmin'])){
    header("Location: cashier.php");
}
    $username = $_SESSION['usernameAdmin'];
    $password = $_SESSION['passwordAdmin'];


    $query = mysqli_query($conn,"SELECT `Username`, `Pass` FROM `admin` WHERE Username = '$username' AND Pass = '$password'");

    $row = mysqli_fetch_array($query);

//----------------------------- Inserting nedw data ----------------------------
//This is for accepting the picture before login
if (isset($_POST['ProductCreation'])) {

    $productName = $_POST['productName'];
    $productID = $_POST['productID'];
    $productPrice = $_POST['productPrice'];
    $Date = $_POST['dateCreate'];
    
    $queryy = "INSERT INTO `stock`(`ProductID`, `ProductName`, `ProductPrice`, `Date`) VALUES ('$productID','$productName','$productPrice', '$Date')";
    $queryq=mysqli_query($conn,$queryy);

    if ($queryq) {
        echo "<script> alert('Product is stored!') </script>";
        echo "<script> document.location.href = 'createProduct.php' </script>";
    }
}

//----------------------------- Showing data ----------------------------
// This is for accepting the picture before login

$limit = 5; // Maximum number of cashier to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for the SQL query

$countQuery = mysqli_query($conn, "SELECT COUNT(`ProductID`) as `total` FROM `stock`");
$totalPages = ceil(mysqli_fetch_array($countQuery)['total'] / $limit); // Total number of pages

$search = isset($_GET['search']) ? $_GET['search'] : ""; // Search term

// Query to fetch cashier with the limit, offset, and search terms applied
$productHistoryTable = mysqli_query($conn, "SELECT * FROM `stock` WHERE `ProductID` LIKE '%$search%' OR `ProductName` LIKE '%$search%' OR `ProductPrice` LIKE '%$search%' OR `Date` LIKE '%$search%' LIMIT $limit OFFSET $offset");

$no = 1 + $offset; // Counter for the table rows

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>ReStocking page</title>
    <style>
        /* Custom style for bottom section */
         /* Custom style for bottom section */
         body {
            background-color: #1c1c1c;
            color: #fff;
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3rem;
        line-height: 1rem;
        background-color: #1c1c1c;
        color: #fff;
        z-index: 999;
    }
    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 3rem;
        line-height: 1rem;
        background-color: #444444;
        font-family: 'Montserrat', Arial;   
        font-weight: bold;
        z-index: 999;
    }
        button a {
            color: transparent;
            text-decoration: none;
            background-color: transparent;
            border: none;
        }
        /* table {
            table-layout: fixed;
             word-wrap: break-word;
        } */

        td, th{
            max-width: 100%;
            white-space: nowrap;
        }

    button {
        background-color: #4d4d4d;
        color: #fff;
        border: none;
    }

    button a:hover {
        background-color: #7a716e;
        color: #fff;
    }
    
    .card-body {
        padding: 1.5rem;
    }
        #productHistoryTable {
        font-size: 0.9rem;
    }

    .dataTables_wrapper {
        margin-top: 1rem;
    }

    .card {
        background-color: #2b2b2b;
        color: #fff;
        border: none;
        border-radius: 10px;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.4);
    }

    .card-title {
        color: #fff;
    }

    .card-text {
        color: #dcdcdc;
    }

    #productHistoryTable_wrapper {
        color: #fff;
    }

    #productHistoryTable_wrapper label {
        color: #fff;
    }

    #productHistoryTable th {
        color: #dcdcdc;
    }

    #productHistoryTable td {
        color: #fff;
    }

    .form-control {
        background-color: #333333;
        border: none;
        border-radius: 5px;
        color: #dcdcdc;
    }

    .form-control:focus {
        border: none;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #2196F3;
        border: none;
        border-radius: 5px;
    }

    .btn-primary:hover {
        background-color: #1E88E5;
    }

    .modal-content {
        background-color: #2a2a2a;
        color: #fff;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-title {
        color: #fff;
    }

    .close {
        color: #fff;
    }

    .pagination {
        justify-content: center;
    }

    .page-item.active .page-link {
        background-color: #2196F3;
        border-color: #2196F3;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: transparent;
        border-color: transparent;
    }
    .navbar {
        background-color: #1c1c1c;
    }
    .navbar-brand {
        color: #fff;
    }

    .navbar-toggler {
        border-color: transparent;
    }

    .navbar-light .navbar-nav .nav-link {
        color: #fff;
    }
    .navbar-light .navbar-nav .nav-link:hover {
        color: #7a716e;
    }

    .navbar-nav a.nav-link {
        color: #fff;
        margin-right: 10px;
    }

    .navbar-nav .active {
        color: #2196F3;
    }

    .area{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: -webkit-linear-gradient(to left, #8f94fb, #4e54c8);
        
    
    }

    .circles{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .circles li{
        position: absolute;
        display: block;
        list-style: none;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.2);
        animation: animate 25s linear infinite;
        bottom: -150px;
        
    }

    .circles li:nth-child(1){
        left: 25%;
        width: 80px;
        height: 80px;
        animation-delay: 0s;
    }


    .circles li:nth-child(2){
        left: 10%;
        width: 20px;
        height: 20px;
        animation-delay: 2s;
        animation-duration: 12s;
    }

    .circles li:nth-child(3){
        left: 70%;
        width: 20px;
        height: 20px;
        animation-delay: 4s;
    }

    .circles li:nth-child(4){
        left: 40%;
        width: 60px;
        height: 60px;
        animation-delay: 0s;
        animation-duration: 18s;
    }

    .circles li:nth-child(5){
        left: 65%;
        width: 20px;
        height: 20px;
        animation-delay: 0s;
    }

    .circles li:nth-child(6){
        left: 75%;
        width: 110px;
        height: 110px;
        animation-delay: 3s;
    }

    .circles li:nth-child(7){
        left: 35%;
        width: 150px;
        height: 150px;
        animation-delay: 7s;
    }

    .circles li:nth-child(8){
        left: 50%;
        width: 25px;
        height: 25px;
        animation-delay: 15s;
        animation-duration: 45s;
    }

    .circles li:nth-child(9){
        left: 20%;
        width: 15px;
        height: 15px;
        animation-delay: 2s;
        animation-duration: 35s;
    }

    .circles li:nth-child(10){
        left: 85%;
        width: 150px;
        height: 150px;
        animation-delay: 0s;
        animation-duration: 11s;
    }



    @keyframes animate {

        0%{
            transform: translateY(0) rotate(0deg);
            opacity: 1;
            border-radius: 0;
        }

        100%{
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
            border-radius: 50%;
        }
    }

    .alert-success {
    background-image: linear-gradient(to bottom, #64c041, #4db546);
    border-color: #64c041;
    color: #fff;
}

.alert-danger {
    background-image: linear-gradient(to bottom, #ff5b5b, #ff2f2f);
    border-color: #ff5b5b;
    color: #fff;
}
    </style>
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">My Cashier</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="homeAdmin.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="createProduct.php">Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="createAdmin.php">Administration</a>
            </li>
            </ul>
        </div>
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link active" href="homeAdmin.php"><?php echo $username; ?></a>
            <a class="nav-item nav-link" href="logoutAdmin.php">Logout</a>
        </div>
    </nav>
</header>
<body>

    <div class="area" >
            <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
            </ul>
    </div >    
    <div class="container mt-3">
    <div class="row">
        <div class="col-lg-3 text-center">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Re-stocking page</h5>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="productID" class="form-label">Insert ID</label>
                            <input type="textarea" name="productID" class="form-control" placeholder="ID" required>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Insert Product name</label>
                            <input type="textarea" name="productName" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Insert Product price</label>
                            <input type="textarea" name="productPrice" class="form-control" placeholder="Price" required>
                        </div>
                        <div class="mb-3">
                            <label for="Date" class="form-label">Select date</label>
                            <input type="date" id="Date" name="dateCreate" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="ProductCreation" class="btn btn-primary">Submit product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mt-1">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title text-center">Product History</h5>
      <div class="row justify-content-end mb-2">
        <div class="col-md-4">
          <form action="" method="get">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search...">
              <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
            </div>
          </form>
        </div>
      </div>
      <div class="table-responsive">
        <table id="productHistoryTable" class="table table-striped">
          <thead>
            <tr>
              <th>NO</th>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Product Price</th>
              <th>Date</th>
              <th>Option</th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($productHistoryTable as $row){
                echo "<tr>
                      <td>$no</td>
                      <td>".$row['ProductID']."</td>
                      <td>".$row['ProductName']."</td>
                      <td>".$row['ProductPrice']."</td>
                      <td>".date('Y-m-d', strtotime($row['Date']))."</td>
                      <td>
                        <button type='button' class='btn btn-danger' onclick=\"location.href='deleteProduct.php?ProductID=".$row['ProductID']."'\"><i class='fa fa-trash'></i> Delete</button>
                        <button class='btn btn-primary modify-btn' data-toggle='modal'data-target='#modifyModal'data-product-id='".$row['ProductID']."'data-product-name='".$row['ProductName']."'data-product-price='".$row['ProductPrice']."'data-product-date='".$row['Date']."'><i class='fa fa-edit'></i> Modify</button>
                      </td>
                    </tr>";
                $no++;
              }
            ?>
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-center mt-3">
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <li class="page-item <?= ($page <= 1 ? "disabled" : "") ?>">
              <a class="page-link" href="<?= ($page <= 1 ? "#" : "?page=".($page-1)) ?>">Previous</a>
            </li>

            <?php for ($i=1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= ($page == $i ? "active" : "") ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?= ($page >= $totalPages ? "disabled" : "") ?>">
              <a class="page-link" href="<?= ($page >= $totalPages ? "#" : "?page=".($page+1)) ?>">Next</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</div>
<!-- Add modify modal HTML -->
<div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLabel">Modify Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="modifyForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ProductID">ID</label>
                        <input type="text" class="form-control" id="ProductID" name="ProductID" required>
                    </div>
                    <div class="form-group">
                        <label for="ProductName">Name</label>
                        <input type="text" class="form-control" id="ProductName" name="ProductName" required>
                    </div>
                    <div class="form-group">
                        <label for="ProductDate">Date</label>
                        <input type="datetime-local" class="form-control" id="ProductDate" name="date" required step="1">
                    </div>
                    <div class="form-group">
                        <label for="ProductPrice">Price</label>
                        <input type="number" class="form-control" id="ProductPrice" name="ProductPrice" required>
                    </div>
                    <input type="hidden" id="OldProductID" name="OldProductID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="navbar-light py-3" >
        <div class="container text-center">
            <span>Stock. &copy; 2023-<?php echo date('Y'); ?>. By Steve.</span>
        </div>
</footer>
    <!-- Rest of the code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <!-- Changes -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#productHistoryTable').DataTable({
                    "pagingType": "full_numbers",
                    "lengthMenu": [
                        [2, 10, 20, -1],
                        [2, 10, 20, "All"]
                    ],
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search record",
                    }
                });
            });
        </script>
            <!-- Add modify button click handler to open modify modal and pre-fill form -->
            <script>
        $(document).ready(function() {
            $('.modify-btn').click(function() {
                var ProductID = $(this).data('product-id');
                var ProductName = $(this).data('product-name');
                var date = $(this).data('product-date');
                var ProductPrice = $(this).data('product-price');
                $('#ProductID').val(ProductID);
                $('#ProductName').val(ProductName);
                $('#ProductDate').val(date.replace(' ', 'T'));
                $('#ProductPrice').val(ProductPrice);
                $('#OldProductID').val(ProductID);
            });
        });
    </script>
    
<!-- Add AJAX form submission for modify form -->
<script>
    $(document).ready(function() {
        $('#modifyForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'updateProduct.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        alert("Modification is successful");
                        location.reload();
                    } else {
                        alert(response);
                    }
                }
            });
        });
    });
</script>
</body>
</html>