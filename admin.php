<?php
require 'Connect.php';

session_start();

if (isset($_POST["submitAdmin"])) {
    $username = $_POST["usernameAdmin"];
    $password = $_POST["passwordAdmin"];

    $query = mysqli_query($conn, "SELECT * FROM `admininfo` WHERE Username = '$username' AND Pass = '$password'");
    $rows = mysqli_fetch_array($query);

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['usernameAdmin'] = $rows['Username'];
        $_SESSION['passwordAdmin'] = $rows['Pass'];
        echo "<script> alert('Login successful!') </script>";
        echo "<script> window.location.href = 'homeAdmin.php' </script>";
    } else {
        echo "<script> alert('Username or Password is incorrect!') </script>";
        echo "<script> window.location.href = 'admin.php' </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
   
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #908986;">
        <div class="container-fluid">
            <a class="navbar-brand" href="Index.php">Admin Login</a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="usernameAdmin" class="form-control" placeholder="Enter username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" name="passwordAdmin" class="form-control" placeholder="Enter password" required>
                            </div>
                            <button type="submit" name="submitAdmin" class="btn btn-primary">Login</button>

                            <?php if (isset($_POST["submitAdmin"]) && mysqli_num_rows($query) == 0): ?>
                                <div class="error">Username or Password is incorrect!</div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>