<?php

use LDAP\Result;

require 'Connect.php';
session_start();


if (!isset($_SESSION['usernameAdmin'])) {
    header("Location: cashier.php");
}
$username = $_SESSION['usernameAdmin'];
$password = $_SESSION['passwordAdmin'];


$query = mysqli_query($conn, "SELECT `Username`, `Pass`, `Namefile`, `Typefile`, `Size`, `Picture` FROM `admin` WHERE Username = '$username' AND Pass = '$password'");

$row = mysqli_fetch_array($query);


if (isset($_POST['RegisterAdmin'])) {

    $usernameC = $_POST['usernameCreate'];
    $passwordC = $_POST['passwordCreate'];

    $gender = $_POST['gender'];
    $dateCreate = $_POST['dateCreate'];

    $fileName = $_FILES['userfile']['name'];
    $fileSize = $_FILES['userfile']['size'];
    $fileTemp = $_FILES['userfile']['tmp_name'];
    $fileType = $_FILES['userfile']['type'];

    if ($fileSize > 1024 * 1024) {
        echo "<script> alert('The file size is too big. Please upload a file with size less than 1MB.') </script>";
        echo "<script> window.location.href = 'createAdmin.php' </script>";
        exit();
    }

    $file = fopen($fileTemp, "r");
    $photouser = fread($file, filesize($fileTemp));
    $photouser = addslashes($photouser);
    fclose($file);

    $fileName = addslashes($fileName);
    $location = "Picture Admin/$fileName";
    move_uploaded_file($fileTemp, $location);
    $queryy = "INSERT INTO `admin` (`Username`, `Pass`, `Gender`, `Date`, `Namefile`, `Typefile`, `Size`, `Picture`) VALUES ('$usernameC','$passwordC', '$gender', '$dateCreate', '$fileName','$fileType','$fileSize','$photouser')";
    $queryq = mysqli_query($conn, $queryy);

    $sqll = "INSERT INTO admininfo (Username, Pass)
    VALUES ('$usernameC','$passwordC')";
    $result = mysqli_query($conn, $sqll);

    if ($queryq) {
        echo "<script> alert('Registered admin is Success!') </script>";
        echo "<script> document.location.href = 'createAdmin.php' </script>";
    }
}

    
if (isset($_POST['RegisterCashier'])) {

    $usernameC = $_POST['usernameCreate'];
    $passwordC = $_POST['passwordCreate'];

    $gender = $_POST['gender'];
    $dateCreate = $_POST['dateCreate'];
    
    $fileName = $_FILES['userfile']['name'];
	$fileSize = $_FILES['userfile']['size'];
    $fileTemp = $_FILES['userfile']['tmp_name'];
	$fileType = $_FILES['userfile']['type'];
    
    if ($fileSize > 1024 * 1024) {
        echo "<script> alert('The file size is too big. Please upload a file with size less than 1MB.') </script>";
        echo "<script> window.location.href = 'createAdmin.php' </script>";
        exit();
    }

    $file = fopen($fileTemp,"r");
    $photouser = fread($file,filesize($fileTemp));
    $photouser = addslashes($photouser);
    fclose($file);
    
    $fileName = addslashes($fileName);
    $location = "Picture Cashier/$fileName";
    move_uploaded_file($fileTemp,$location);
    $queryy = "INSERT INTO `cashier`  (`Username`, `Pass`, `Gender`, `Date` , `Namefile`, `Typefile`, `Size`, `Picture`)  VALUES ('$usernameC','$passwordC','$gender','$dateCreate','$fileName','$fileType','$fileSize','$photouser')";
    $queryq=mysqli_query($conn,$queryy);

    $sqll = "INSERT INTO cashierinfo (Username, Pass)
    VALUES ('$usernameC','$passwordC')";
    $result = mysqli_query($conn, $sqll);

    if ($queryq) {
        echo "<script> alert('Registered cashier is Success!') </script>";
        echo "<script> document.location.href = 'createCashier.php' </script>";
    }
}
$limit = 1;
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$countQuery = mysqli_query($conn, "SELECT COUNT(`Username`) as `total` FROM `admin`");
$totalPages = ceil(mysqli_fetch_array($countQuery)['total'] / $limit); 

$search = isset($_GET['search']) ? $_GET['search'] : ""; 

$adminHistoryTable = mysqli_query($conn, "SELECT * FROM `admin` WHERE `Username` LIKE '%$search%' OR `Gender` LIKE '%$search%' OR `Date` LIKE '%$search%' OR `Namefile` LIKE '%$search%' LIMIT $limit OFFSET $offset");

$no = 1 + $offset;
// _____________________________CASHIER_______________________________
$pages = isset($_GET['pages']) ? $_GET['pages'] : 1; 
$offsets = ($pages - 1) * $limit; 
$countQuerys = mysqli_query($conn, "SELECT COUNT(`Username`) as `total` FROM `cashier`");
$totalPagess = ceil(mysqli_fetch_array($countQuerys)['total'] / $limit); // 

$searchS = isset($_GET['searchs']) ? $_GET['searchs'] : ""; 
$cashierHistoryTable = mysqli_query($conn, "SELECT * FROM `cashier` WHERE `Username` LIKE '%$searchS%' OR `Gender` LIKE '%$searchS%' OR `Date` LIKE '%$searchS%' OR `Namefile` LIKE '%$searchS%' LIMIT $limit OFFSET $offsets");
$nos = 1 + $offsets;
?>
<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
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
            /* white-space: nowrap; */
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

    #adminHistoryTable_wrapper, #cashierHistoryTable_wrapper {
        color: #fff;
    }
    #cashierHistoryTable_wrapper label, #adminHistoryTable_wrapper label {
        color: #fff;
    }

    #adminHistoryTable th, #cashierHistoryTable th {
        color: #dcdcdc;
    }

    #adminHistoryTable td, #cashierHistoryTable td {
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
<div class="container mt-3 admin-interface">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                        <h5 class="card-title">Create Admin Dashboard</h5>
                        <button type="button" class="btn btn-primary btn-switch" id="switchToCashierBtn">Switch to Cashier</button>

                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="usernameCreate" class="form-label">Insert admin name</label>
                            <input type="textarea" name="usernameCreate" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordCreate" class="form-label">Insert admin password</label>
                            <input type="password" name="passwordCreate" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Select gender</label>
                            <select name="gender" id="gender" class="form-select" required style="background-color: #333333; color: #dcdcdc; border: none; border-radius: 5px;">
                                <option value="" selected disabled hidden>Choose Gender</option>
                                <option value="Male" style="background-color: #2b2b2b; color: #fff;">Male</option>
                                <option value="Female" style="background-color: #2b2b2b; color: #fff;">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateCreate" class="form-label">Select date</label>
                            <input type="date" id="dateCreate" name="dateCreate" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="userfile" class="form-label">Select file</label>
                            <input type="file" name="userfile" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="RegisterAdmin" class="btn btn-primary">Register admin data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">

            <div class="card-body">
                    <h5 class="card-title">Admin Account History</h5>
                    <div class="row justify-content-end mb-3">
                        <div class="col-md-4">
                            <form action="" method="get">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search...">
                                    <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                        <table id="adminHistoryTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Admin name</th>
                                    <th>Gender</th>
                                    <th>Join since</th>
                                    <th>Picture</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($adminHistoryTable as $row){
                                    echo "<tr>
                                        <td>$no</td>
                                        <td>".$row['Username']."</td>
                                        <td>".$row['Gender']."</td>
                                        <td>".$row['Date']."</td>
                                        <td><img src='Picture Admin/".$row['Namefile']."' width='100' height='65'></td>
                                        <td>
                                            <button type='button' class='btn btn-danger' onclick=\"location.href='deleteAdmin.php?Username=".$row['Username']."'\">Delete</button>
                                        </td>           
                                    </tr>";
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>

                </div>
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
<div class="container mt-3 d-none cashier-interface">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                <div class="d-flex justify-content-between">
                        <h5 class="card-title">Create Cashier Dashboard</h5>
                        <button type="button" class="btn btn-primary btn-switch" id="switchToAdminBtn">Switch to Admin</button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="usernameCreate" class="form-label">Insert cashier name</label>
                            <input type="textarea" name="usernameCreate" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordCreate" class="form-label">Insert cashier password</label>
                            <input type="password" name="passwordCreate" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Select gender</label>
                            <select name="gender" id="gender" class="form-select" required style="background-color: #333333; color: #dcdcdc; border: none; border-radius: 5px;">
                                <option value="" selected disabled hidden>Choose Gender</option>
                                <option value="Male" style="background-color: #2b2b2b; color: #fff;">Male</option>
                                <option value="Female" style="background-color: #2b2b2b; color: #fff;">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dateCreate" class="form-label">Select date</label>
                            <input type="date" id="dateCreate" name="dateCreate" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="userfile" class="form-label">Select file</label>
                            <input type="file" name="userfile" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="RegisterCashier" class="btn btn-primary">Register cashier data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Cashier Account History</h5>

                    <div class="row justify-content-end mb-3">
                        <div class="col-md-4">
                            <form action="" method="get">
                                <div class="input-group">
                                    <input type="text" name="searchs" class="form-control" placeholder="Search...">
                                    <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                        <table id="cashierHistoryTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cashier name</th>
                                    <th>Gender</th>
                                    <th>Recruited since</th>
                                    <th>Picture</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $nos = 1;
                                foreach ($cashierHistoryTable as $row){
                                    echo "<tr>
                                        <td>$no</td>
                                        <td>".$row['Username']."</td>
                                        <td>".$row['Gender']."</td>
                                        <td>".$row['Date']."</td>
                                        <td><img src='Picture Cashier/".$row['Namefile']."' width='100' height='65'></td>
                                        <td>
                                            <button type='button' class='btn btn-danger' onclick=\"location.href='deleteCashier.php?Username=".$row['Username']."'\">Delete</button>
                                        </td>           
                                    </tr>";
                                    $nos++;
                                }
                                ?>
                            </tbody>
                        </table>

                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?= ($pages <= 1 ? "disabled" : "") ?>">
                            <a class="page-link" href="<?= ($pages <= 1 ? "#" : "?pages=".($pages-1)) ?>">Previous</a>
                        </li>

                        <?php for ($is=1; $is <= $totalPagess; $is++): ?>
                            <li class="page-item <?= ($pages == $is ? "active" : "") ?>">
                                <a class="page-link" href="?pages=<?= $is ?>&searchs=<?= $searchS ?>"><?= $is ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($pages >= $totalPagess ? "disabled" : "") ?>">
                            <a class="page-link" href="<?= ($pages >= $totalPagess ? "#" : "?pages=".($pages+1)) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>
    <footer class="navbar-light py-3" >
        <div class="container text-center">
            <span>Account Builder. &copy; 2023-<?php echo date('Y'); ?>. By Steve.</span>
        </div>
            </footer>
            <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
            </script>
            <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js">
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js">
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js">
            </script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#adminHistoryTable').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [
                    [1, 5, 10, -1],
                    [1, 5, 10, "All"]
                ],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search record",
                }
            });
        });
        $(document).ready(function() {
                $('#cashierHistoryTable').DataTable({
                    "pagingType": "full_numbers",
                    "lengthMenu": [
                        [2, 10, 20, -1],
                        [2, 10, 20, "All"]
                    ],
                    responsive: true,
                    language: {
                        searchS: "_INPUT_",
                        searchPlaceholder: "Search record",
                    }
                });
            });
        $(document).ready(function() {
  $("#switchToAdminBtn").click(function() {
    $(".admin-interface").removeClass("d-none");
    $(".cashier-interface").addClass("d-none");
    $("#switchToCashierBtn").removeClass("btn-secondary").addClass("btn-primary").html("Switch to Cashier");
    $(this).removeClass("btn-primary").addClass("btn-secondary").html("<span class='spinner-border spinner-border-sm mr-2'></span>Switching...");
    setTimeout(function() {
      $("#switchToAdminBtn").removeClass("btn-secondary").addClass("btn-primary").html("Switch to Admin");
      $(".admin-interface, .cashier-interface").css({ "justify-content": "center", "align-items": "center", "width": "100%", "height":"100%" });
    }, 1000);
  });

  $("#switchToCashierBtn").click(function() {
    $(".admin-interface").addClass("d-none");
    $(".cashier-interface").removeClass("d-none");
    $("#switchToAdminBtn").removeClass("btn-primary").addClass("btn-secondary").html("Switch to Admin");
    $(this).removeClass("btn-secondary").addClass("btn-primary").html("<span class='spinner-border spinner-border-sm mr-2'></span>Switching...");
    setTimeout(function() {
      $("#switchToCashierBtn").removeClass("btn-primary").addClass("btn-secondary").html("Switch to Cashier");
      $(".admin-interface, .cashier-interface").css({ "justify-content": "center", "align-items": "center", "width": "100%", "height":"100%" });
    }, 1000);
  });
});

    </script>
</body>