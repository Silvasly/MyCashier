<?php 
require 'Connect.php';
session_start();

if(!isset($_SESSION['usernameAdmin'])){
  header("Location: cashier.php");
}

$username = $_SESSION['usernameAdmin'];
$password = $_SESSION['passwordAdmin'];
// $_SESSION['usernameCreate']
// $_SESSION['passwordCreate'] 

$query = mysqli_query($conn,"SELECT `Username`, `Pass`, `Gender`, `Date`, `Namefile`, `Typefile`, `Size`, `Picture` FROM `admin` WHERE Username = '$username' AND Pass = '$password'");

$row = mysqli_fetch_array($query);


$limit = 5; // Maximum number of transactions to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for the SQL query

$countQuery = mysqli_query($conn, "SELECT COUNT(`NoTransaction`) as total FROM `transaction`");
$totalPages = ceil(mysqli_fetch_array($countQuery)['total'] / $limit); // Total number of pages

$search = isset($_GET['search']) ? $_GET['search'] : ""; // Search term

// Query to fetch transactions with the limit, offset, and search terms applied
$AdminHistory = mysqli_query($conn, "SELECT * FROM `transaction` WHERE `Cashier` LIKE '%$search%' OR `NoTransaction` LIKE '%$search%' OR `Date` LIKE '%$search%' OR `Total` LIKE '%$search%' LIMIT $limit OFFSET $offset");

$no = 1 + $offset; // Counter for the table rows
?>
<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
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
    button {
        background-color: #4d4d4d;
        color: #fff;
        border: none;
    }
    button a {
        color: #fff;
        text-decoration: none;
        background-color: transparent;
        border: none;
    }

    button a:hover {
        background-color: #7a716e;
        color: #fff;
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

    #transactionTable_wrapper {
        color: #fff;
    }

    #transactionTable_wrapper label {
        color: #fff;
    }

    #transactionTable th {
        color: #dcdcdc;
    }

    #transactionTable td {
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

    </style>
</head>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark"  >
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
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="pictureAdmin.php?usernameAdmin=<?= $username; ?>" class="img-fluid" alt="User Profile Picture"/>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Personal Information</h5>
                                <p class="card-text">Name: <?= $row['Username']; ?></p>
                                <p class="card-text">Gender: <?= $row['Gender']; ?></p>
                                <p class="card-text">Account type: Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Transaction History</h5>
                        <?php
                            // Search bar form
                            echo '<form class="d-flex mb-3">
                                    <input class="form-control me-2" type="search" placeholder="Search" name="search" aria-label="Search" value="'.$search.'">
                                    <button class="btn btn-outline-success" type="submit">Search</button>
                                </form>';
                        ?>

                        <table id="transactionTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Transaction number</th>
                                    <th>Time</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($AdminHistory as $row) {
                                        echo "<tr>
                                            <td>$no</td>
                                            <td>".$row['Cashier']."</td>
                                            <td>".$row['NoTransaction']."</td>
                                            <td>".$row['Date']."</td>
                                            <td>".$row['Total']."</td>
                                            <td><button> <a href=Delete.php?NoTransaction=".$row['NoTransaction']."> Delete </a> </button>
                        <button class='modify-btn' data-toggle='modal' data-target='#modifyModal' data-no='".$row['NoTransaction']."' data-cashier='".$row['Cashier']."' data-date='".$row['Date']."' data-total='".$row['Total']."'>Modify</button>
                                            </td>                        
                                        </tr>";
                                        $no++;
                                    }
                                ?>

                            </tbody>
                        </table>

                        <?php
                            // Pagination links
                            echo '<div class="d-flex justify-content-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <li class="page-item '.($page <= 1 ? "disabled" : "").'">
                                                <a class="page-link" href="'.($page <= 1 ? "#" : "?page=".($page-1)).'">Previous</a>
                                            </li>';

                            for ($i=1; $i <= $totalPages; $i++) {
                                echo '<li class="page-item '.($page == $i ? "active" : "").'"><a class="page-link" href="?page='.$i.'&search='.$search.'">'.$i.'</a></li>';
                            }

                            echo '<li class="page-item '.($page >= $totalPages ? "disabled" : "").'">
                                    <a class="page-link" href="'.($page >= $totalPages ? "#" : "?page=".($page+1)).'">Next</a>
                                </li>
                                        </ul>
                                    </nav>
                                </div>';
                        ?>
                </div>
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
                        <label for="modifyCashier">Cashier</label>
                        <input type="text" class="form-control" id="modifyCashier" name="cashier" required>
                    </div>
                    <div class="form-group">
                        <label for="modifyDate">Date</label>
                        <input type="datetime-local" class="form-control" id="modifyDate" name="date" required step="1">
                    </div>
                    <div class="form-group">
                        <label for="modifyTotal">Total</label>
                        <input type="number" class="form-control" id="modifyTotal" name="total" required>
                    </div>
                    <input type="hidden" id="modifyNoTransaction" name="noTransaction">
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
        <span>Copyright © 2023 
            <?php 
            $startYear = 2023;
            $currentYear = date('Y');
            echo $startYear . (($startYear != $currentYear) ? '-' . $currentYear : '');
            ?> 
            Adminstrator Dashboard. By Steve.</span>
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
            $('#transactionTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [5, 10, 20, -1],
                    [5, 10, 20, "All"]
                ],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "search record",
                }
            });
        });
    </script>
    <!-- Add modify button click handler to open modify modal and pre-fill form -->
    <script>
        $(document).ready(function() {
            $('.modify-btn').click(function() {
                var noTransaction = $(this).data('no');
                var cashier = $(this).data('cashier');
                var date = $(this).data('date');
                var total = $(this).data('total');
                $('#modifyNoTransaction').val(noTransaction);
                $('#modifyCashier').val(cashier);
                $('#modifyDate').val(date.replace(' ', 'T'));
                $('#modifyTotal').val(total);
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
                url: 'modifyTransaction.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        alert("Modification is succes");
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