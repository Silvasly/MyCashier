<?php

use LDAP\Result;

require 'Connect.php';
session_start();

//--------------------------------- login ----------------------------
//This is for accepting the picture before login
if(!isset($_SESSION['usernameAdmin'])){
    header("Location: admin.php");
}
    $username = $_SESSION['usernameAdmin'];
    $password = $_SESSION['passwordAdmin'];


    $query = mysqli_query($conn,"SELECT `Username`, `Pass`, `Namefile`, `Typefile`, `Size`, `Picture` FROM `admin` WHERE Username = '$username' AND Pass = '$password'");

    $row = mysqli_fetch_array($query);

//----------------------------- Inserting nedw data ----------------------------
//This is for accepting the picture before login
    
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

//----------------------------- Showing data ----------------------------
// This is for accepting the picture before login

$limit = 1; // Maximum number of cashier to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for the SQL query

$countQuerys = mysqli_query($conn, "SELECT COUNT(`Username`) as `total` FROM `cashier`");
$totalPagess = ceil(mysqli_fetch_array($countQuerys)['total'] / $limit); // Total number of pages

$search = isset($_GET['search']) ? $_GET['search'] : ""; // Search term

// Query to fetch cashier with the limit, offset, and search terms applied
$cashierHistoryTable = mysqli_query($conn, "SELECT * FROM `cashier` WHERE `Username` LIKE '%$search%' OR `Gender` LIKE '%$search%' OR `Date` LIKE '%$search%' OR `Namefile` LIKE '%$search%' LIMIT $limit OFFSET $offset");

$no = 1 + $offset; // Counter for the table rows

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
        /* Custom style for bottom section */
        body {
            background-color: #d9d9d9;
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
            background-color: #908986;
            z-index: 999;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3rem;
            line-height: 1rem;
            background-color: #908986;
            z-index: 999;
        }
        button a {
            color: transparent;
            text-decoration: none;
            background-color: transparent;
            border: none;
        }

        button a:hover {
            color: #7a716e;
        }

    </style>
</head>
<header>
        <nav class="navbar navbar-expand-lg navbar-light"  >
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
            <li class="nav-item">
                <a class="nav-link" href="createCashier.php">Cashier Builder</a>
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
    <div class="container mt-3">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cashier builder homepage</h5>
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
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="" selected disabled hidden>None</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
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
                    <div class="table-responsive">
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
                                $no = 1;
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
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?= ($page <= 1 ? "disabled" : "") ?>">
                            <a class="page-link" href="<?= ($page <= 1 ? "#" : "?page=".($page-1)) ?>">Previous</a>
                        </li>

                        <?php for ($i=1; $i <= $totalPagess; $i++): ?>
                            <li class="page-item <?= ($page == $i ? "active" : "") ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $totalPagess ? "disabled" : "") ?>">
                            <a class="page-link" href="<?= ($page >= $totalPagess ? "#" : "?page=".($page+1)) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
    <footer class="navbar-light py-3" >
        <div class="container text-center">
            <span>Cashier Builder Dashboard. &copy; 2023-<?php echo date('Y'); ?>. By Steve.</span>
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
        <script>
            $(document).ready(function() {
                $('#cashierHistoryTable').DataTable({
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
</body>