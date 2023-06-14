<?php
require 'Connect.php';

session_start();

if (isset($_POST["submitCashier"])) {
    $username = $_POST["usernameCashier"];
    $password = $_POST["passwordCashier"];

    $query = mysqli_query($conn, "SELECT * FROM `cashierinfo` WHERE Username = '$username' AND Pass = '$password'");

    $rows = mysqli_fetch_array($query);

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['usernameCashier'] = $rows['Username'];
        $_SESSION['passwordCashier'] = $rows['Pass'];
        echo '<div class="alert alert-success alert-dismissible fade show m-1 text-center position-fixed start-50 translate-middle-x" role="alert" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9998; animation: fadeOut 5s forwards;">
            <span>Login successful!</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    echo '<script>
            setTimeout(function() {
            window.location.href = "homeCashier.php";
            }, 3800);
        </script>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show m-1 text-center position-fixed start-50 translate-middle-x"  role="alert" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9998; animation: fadeOut 5s forwards;">
                <span>Incorrect username or password!</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        echo '<script>
                setTimeout(function() {
                window.location.href = "cashier.php";
                }, 3800);
        </script>';
        }
}

if (isset($_POST["submitAdmin"])) {
    $username = $_POST["usernameAdmin"];
    $password = $_POST["passwordAdmin"];

    $query = mysqli_query($conn, "SELECT * FROM `admininfo` WHERE Username = '$username' AND Pass = '$password'");
    $rows = mysqli_fetch_array($query);

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['usernameAdmin'] = $rows['Username'];
        $_SESSION['passwordAdmin'] = $rows['Pass'];
        echo '<div class="alert alert-success alert-dismissible fade show m-1 text-center position-fixed start-50 translate-middle-x" role="alert" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9998; animation: fadeOut 5s forwards;">
            <span>Login successful!</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    echo '<script>
            setTimeout(function() {
            window.location.href = "homeAdmin.php";
            }, 3800);
        </script>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show m-1 text-center position-fixed start-50 translate-middle-x"  role="alert" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9998; animation: fadeOut 5s forwards;">
                <span>Incorrect username or password!</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        echo '<script>
                setTimeout(function() {
                window.location.href = "cashier.php";
                }, 3800);
        </script>';
        }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @keyframes fadeOut {
            0% {opacity: 0}
            50% {opacity: 1}
            100% {opacity: 0}
        }
        body {
            background-color: black;
            width: 100%;

        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background-color: #3c4241;
        }

        .popup-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .popup-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0px 0px 10px #000;
        }

        .popup-form-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .popup-form {
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #000;
            position: relative;
            max-width: 80%;
            max-height: 80%;
            overflow: auto;
        }

        .popup-form .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #000;
            cursor: pointer;
        }

        .popup-form .form-group {
            margin-bottom: 20px;
        }

        .popup-form .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .popup-form .form-group input[type="text"],
        .popup-form .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            box-shadow: 0px 0px 5px #000;
        }

        .popup-form .form-group input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        .popup-form .form-group input[type="submit"]:hover {
            background-color: #218838;
        }

        .blur-background {
            filter: blur(2px);
            pointer-events: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: opacity 0.5s ease-out;
            opacity: 0.8;
            z-index: 999;        
            padding: 0;
            margin: 0;
        }

        .blur-background.active {
            opacity: 1;
            pointer-events: all;
            padding: 0;
        }

        .blur-background.close {
            opacity: 0;
            pointer-events: none;
        }

        .login-background {
            background-image: url('./Background picture/Home/As.gif');
            background-size: cover;
            background-position: center;
            height: 100vh;
            position: relative;
            z-index: 0;
            overflow: hidden;
        } 
        @import url("https://fonts.googleapis.com/css?family=Montserrat:700");
        .about-background {
            /* background-image: url('./Background picture/About/Ab.gif'); */
            background-size: cover;
            background-position: 25% 75%;
            height: 100vh;
            position: relative;
            z-index: 0;
            background-color: black;
            overflow: hidden;
            font-family: "Montserrat", sans-serif;
        }

        .about-header {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
            color: grey;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }


        .cube {
            position: absolute;
            top: 80vh;
            left: 45vw;
            width: 10px;
            height: 10px;
            border: solid 1px grey;
            transform-origin: top left;
            transform: scale(0) rotate(0deg) translate(-50%, -50%);
            -webkit-animation: cube 20s ease-in forwards infinite;
                    animation: cube 20s ease-in forwards infinite;
        }
        .cube:nth-child(2n) {
            border-color: honeydew;
        }
        .cube:nth-child(2) {
            -webkit-animation-delay: 2s;
                    animation-delay: 2s;
            left: 25vw;
            top: 40vh;
        }
        .cube:nth-child(3) {
            -webkit-animation-delay: 4s;
                    animation-delay: 4s;
            left: 75vw;
            top: 50vh;
        }
        .cube:nth-child(4) {
            -webkit-animation-delay: 6s;
                    animation-delay: 6s;
            left: 90vw;
            top: 79vh;
        }
        .cube:nth-child(5) {
            -webkit-animation-delay: 8s;
                    animation-delay: 8s;
            left: 10vw;
            top: 30vh;
        }
        .cube:nth-child(6) {
            -webkit-animation-delay: 10s;
                    animation-delay: 10s;
            left: 50vw;
            top: 55vh;
        }
        .cube:nth-child(7) {
            -webkit-animation-delay: 12s;
                    animation-delay: 12s;
            left: 15vw;
            top: 88vh;
        }
        .cube:nth-child(8) {
            -webkit-animation-delay: 14s;
                    animation-delay: 14s;
            left: 77vw;
            top: 98vh;
        }
        .cube:nth-child(9) {
            -webkit-animation-delay: 16s;
                    animation-delay: 16s;
            left: 30vw;
            top: 12vh;
        }
        .cube:nth-child(10) {
            -webkit-animation-delay: 18s;
                    animation-delay: 18s;
            left: 85vw;
            top: 10vh;
        }
        .cube:nth-child(11) {
            -webkit-animation-delay: 20s;
                    animation-delay: 20s;
            left: 50vw;
            top: 15vh;
        }

        @-webkit-keyframes cube {
        from {
            transform: scale(0) rotate(0deg) translate(-50%, -50%);
            opacity: 1;
        }
        to {
            transform: scale(20) rotate(960deg) translate(-50%, -50%);
            opacity: 0;
        }
        }

        @keyframes cube {
        from {
            transform: scale(0) rotate(0deg) translate(-50%, -50%);
            opacity: 1;
        }
        to {
            transform: scale(20) rotate(960deg) translate(-50%, -50%);
            opacity: 0;
        }
        }
        .about-background .card {
            background: linear-gradient(to bottom right, #000000, #434343);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 50px;
            padding-bottom: 10px;
            box-shadow: 0px 0px 20px black;
        }
        .about-background .card h2.about-header {
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
            color: white;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

    </style>
</head>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: transparant;   font-family: 'Montserrat', Arial;   font-weight: bold;">
        <a class="navbar-brand" href="#LoginUp">My Cashier</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#aboutSection">About</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<body>
    <div class="popup-container">
    <button type="button" class="btn btn-primary" id="popupButton" data-toggle="modal" data-target="#popupForm">
  <i class="fa fa-question-circle"></i> Not finding what you need?
</button>
    </div>
        <div class="container-fluid"id = "LoginUp">
        <div class="row justify-content-center align-items-center login-background">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <H2 style="            
                        font-weight: bold;
                        text-align: center;
                        color: Black;">Login form </H2>
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="usernameCashier" class="form-control" placeholder="Enter username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" name="passwordCashier" class="form-control" placeholder="Enter password" required>
                            </div>
                            <button type="submit" name="submitCashier" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<section id="aboutSection" class="container-fluid">
    <div class="row justify-content-center align-items-center about-background">
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
    <div class="cube"></div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <h2 class="about-header mb-4 text-center">About</h2>
                    <p class="text-center">This is a project made for the Software Engineer class. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
                </div>
            </div>
        </div>
    </div>
</section>

    <div class="popup-form-container" id="popupFormContainer">
        <div class="popup-form">
            <div class="close-button" id="closeButton">&times;</div>
            <H4 style="            
                    font-weight: bold;
                    text-align: left;
                    bottom: 20px;
                    color: #1c202b;">Admin</H3>
            <form action="" method="post">
                <div class="form-group">
                    <label for="usernameAdmin">Username:</label>
                    <input type="text" name="usernameAdmin" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="passwordAdmin">Password:</label>
                    <input type="password" name="passwordAdmin" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="submitAdmin" value="Login" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
    
    <script>
        const popupButton = document.getElementById('popupButton');
        const popupFormContainer = document.getElementById('popupFormContainer');
        const closeButton = document.getElementById('closeButton');
        const blurBackground = document.querySelectorAll('header, .login-background, .about-background > .container-fluid > .row')[0];
        let popupOpen = false;

        popupButton.addEventListener('click', () => {
            if (!popupOpen) {
                popupFormContainer.style.display = 'flex';
                blurBackground.classList.add('blur-background');
                popupOpen = true;
            } else {
                popupFormContainer.style.display = 'none';
                blurBackground.classList.remove('blur-background');
                popupOpen = false;
            }
        });
        closeButton.addEventListener('click', () => {
            popupFormContainer.style.display = 'none';
            blurBackground.classList.remove('blur-background');
            popupOpen = false;
        });
        document.querySelector('a[href="#aboutSection"]').addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('#aboutSection').scrollIntoView({
            behavior: 'smooth'
        });
        });
        document.querySelector('a[href="#LoginUp"]').addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('#LoginUp').scrollIntoView({
            behavior: 'smooth'
        });
        });
    </script>
</body>

</html>