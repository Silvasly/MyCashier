<?php 
require 'Connect.php';
session_start();

if(!isset($_SESSION['usernameCashier'])){
  header("Location: cashier.php");
}

$username = $_SESSION['usernameCashier'];
$password = $_SESSION['passwordCashier'];

$query = mysqli_query($conn,"SELECT `Username`, `Pass`, `Gender`, `Date`, `Namefile`, `Typefile`, `Size`, `Picture` FROM `cashier` WHERE Username = '$username' AND Pass = '$password'");

$row = mysqli_fetch_array($query);

$limit = 5; // Maximum number of transactions to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for the SQL query

$countQuery = mysqli_query($conn, "SELECT COUNT(`NoTransaction`) as total FROM `transaction`");
$totalPages = ceil(mysqli_fetch_array($countQuery)['total'] / $limit); // Total number of pages

$search = isset($_GET['search']) ? $_GET['search'] : ""; // Search term

// Query to fetch transactions with the limit, offset, and search terms applied
$CashierHistory = mysqli_query($conn, "SELECT * FROM `transaction` WHERE `Cashier` LIKE '%$search%' OR `NoTransaction` LIKE '%$search%' OR `Date` LIKE '%$search%' OR `Total` LIKE '%$search%' LIMIT $limit OFFSET $offset");

$no = 1 + $offset; // Counter for the table rows
?>

<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        background-color: #3c4241;
        font-family: 'Montserrat', Arial;   
        font-weight: bold;
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
        background-color: #3c4241;
        font-family: 'Montserrat', Arial;   
        font-weight: bold;
        color: #fff;
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
        background-color: #3c4241;
    }
    .navbar-brand {
        color: lightblue;
    }

    .navbar-toggler {
        border-color: transparent;
    }

    .navbar-light .navbar-nav .nav-link {
        color: #1c1c1c;
    }
    .navbar-light .navbar-nav .nav-link:hover {
        color: #fff;
    }

    .navbar-nav a.nav-link {
        color: #fff;
        margin-right: 10px;
    }

    .navbar-nav .active {
        color: #2196F3;
    }
    
.snow, .snow:before, .snow:after {
  position: fixed;
  top: -600px;
  left: 0;
  bottom: 0;
  right: 0;
  background-image: radial-gradient(5px 5px at 562px 49px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 478px 76px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 224px 431px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 440px 267px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 348px 114px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 66px 11px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 142px 502px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 383px 307px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 142px 537px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 390px 507px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 29px 194px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 463px 211px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 290px 198px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 382px 119px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 170px 315px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 445px 179px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 177px 36px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 204px 68px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 437px 169px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 410px 169px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 247px 514px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 93px 354px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 494px 352px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 52px 433px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 574px 468px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 386px 116px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 8px 389px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 463px 227px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 301px 421px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 73px 133px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 577px 207px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 226px 591px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 555px 503px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 199px 533px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 247px 569px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 536px 256px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 216px 548px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 99px 262px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 156px 422px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 180px 29px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 308px 125px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 98px 135px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 252px 381px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 462px 526px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 303px 387px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 57px 320px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 159px 454px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 302px 443px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 93px 356px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 546px 473px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 20px 229px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 53px 299px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 313px 296px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 306px 319px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 311px 296px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 513px 110px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 67px 87px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 397px 203px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(4px 4px at 422px 286px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 312px 7px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 533px 455px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 61px 276px, rgba(255, 255, 255, 0.7) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 261px 425px, rgba(255, 255, 255, 0.9) 50%, rgba(0, 0, 0, 0)), radial-gradient(5px 5px at 174px 164px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 591px 54px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 570px 411px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 348px 234px, rgba(255, 255, 255, 0.6) 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 556px 149px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(6px 6px at 402px 308px, white 50%, rgba(0, 0, 0, 0)), radial-gradient(3px 3px at 145px 270px, rgba(255, 255, 255, 0.8) 50%, rgba(0, 0, 0, 0));
  background-size: 600px 600px;
  animation: snow 3s linear infinite;
  content: "";
}

.snow:after {
  margin-left: -200px;
  opacity: 0.4;
  animation-duration: 6s;
  animation-direction: reverse;
  filter: blur(3px);
}

.snow:before {
  animation-duration: 9s;
  animation-direction: reverse;
  margin-left: -300px;
  opacity: 0.65;
  filter: blur(1.5px);
}

@keyframes snow {
  to {
    transform: translateY(600px);
  }
}
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark" >
      <a class="navbar-brand" href="#">My Cashier</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="homeCashier.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="createTransaction.php">Record</a>
          </li>
        </ul>
      </div>
      <div class="navbar-nav ml-auto">
        <a class="nav-item nav-link active" href="homeCashier.php"><?php echo $username; ?></a>
        <a class="nav-item nav-link" href="logoutCashier.php">Logout</a>
      </div>
    </nav>
  </header>
  <div class="snow"></div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="pictureCashier.php?usernameCashier=<?= $username; ?>" class="img-fluid" alt="User Profile Picture"/>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Personal Information</h5>
                                <p class="card-text">Name: <?= $row['Username']; ?></p>
                                <p class="card-text">Gender: <?= $row['Gender']; ?></p>
                                <p class="card-text">Join since: <?= $row['Date']; ?></p>
                                <p class="card-text">Account type: Cashier</p>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($CashierHistory as $row) {
                                        echo "<tr>
                                            <td>$no</td>
                                            <td>".$row['Cashier']."</td>
                                            <td>".$row['NoTransaction']."</td>
                                            <td>".$row['Date']."</td>
                                            <td>".$row['Total']."</td>
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
<footer class="navbar-light py-3" >
    <div class="container text-center">
        <span>Copyright © 2023 
            <?php 
            $startYear = 2023;
            $currentYear = date('Y');
            echo $startYear . (($startYear != $currentYear) ? '-' . $currentYear : '');
            ?> 
            Cashier Dashboard. By Steve.</span>
    </div>
    </footer>
    <!-- Rest of the code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

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
</body>
</html>