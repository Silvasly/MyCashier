
<?php 
require_once 'Connect.php';

session_start();

if(!isset($_SESSION['usernameCashier'])){
  header("Location: index.php");
}

$usernameCashier = $_SESSION['usernameCashier'];

// Generate transaction ID
$query = "SELECT NoTransaction FROM transaction ORDER BY NoTransaction DESC";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$lastid = $row['NoTransaction'];
if(empty($lastid)) {
  $number = "E-0000001";
} else {
  $idd = str_replace("E-", "", $lastid);
  $id = str_pad($idd + 1, 7, 0, STR_PAD_LEFT);
  $number = 'E-'.$id;
}

// Add item to cart
if (isset($_POST['addtocart'])) {
  $_POST['vegetable'];
  if (!empty($_POST['vegetable']) && !empty($_POST['quantity'])) {
    if (addtocart($_POST) > 0) {
      // Item added successfully, do nothing
    } else {
      echo mysqli_error($conn);
    }
  }
}

// Get cart items
$cart = mysqli_query($conn, "SELECT * FROM cart WHERE username = '$usernameCashier'");

// Calculate subtotal, tax, and gross total
$subtotal = 0;
foreach($cart as $cr) {
  $subtotal += $cr['total'];
}
$tax = $subtotal * 0.05;
$gross_total = $subtotal + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
      body {
        background-color: #1c1c1c;
        color: #fff;
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
    .result {
      color: red;
    }
    td, th {
      text-align: center;
      color: #dcdcdc;
    }
    main {
      margin-top: 1rem;
      margin-bottom: 1rem;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
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
        <a class="nav-item nav-link active" href="homeCashier.php"><?php echo $usernameCashier; ?></a>
        <a class="nav-item nav-link" href="logoutCashier.php">Logout</a>
      </div>
    </nav>
  </header>
  <main>
  <div class="snow"></div>
    <section class="container mt-4">
      <h4 class="text-center mb-4">Record Page</h4>
      <div class="row">
        <div class="col-md-5">
          <div class="card">
            <div class="card-header">Add Item to Cart</div>
            <div class="card-body">
              <form action="" method="POST">
                <div class="form-group">
                <label for="vegetable">Product</label>
                  <select class="form-control" id="vegetable" name="vegetable" required>
                    <option value="">Select Product</option>
                    <?php 
                      $sql = "SELECT * FROM stock";
                      $query = mysqli_query($conn, $sql);
                      while($row = mysqli_fetch_assoc($query)) {
                        echo '<option value="'. $row['ProductName'] . '">' . $row['ProductName'] . '</option>';
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                <label for="quantity">Quantity</label>
                  <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                </div>
                <input type="hidden" name="username" value="<?php echo $usernameCashier; ?>">
                <button type="submit" class="btn btn-primary" name="addtocart" id="addtocart" disabled>Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-header">Receipt</div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <span>Date: <?php echo date('d F Y'); ?></span><br>
                  <span>Time: <span id="time"></span></span>
                </div>
                <div class="col-md-6 text-right">
                  <span id="day"></span> <span id="year"></span>
                </div>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach($cart as $cr) : ?>
                    <tr>
                      <td><?php echo $i; $i++; ?></td>
                      <td><?php echo $cr['product_name']; ?></td>
                      <td><?php echo $cr['quantity']; ?></td>
                      <td><?php echo $cr['price']; ?></td>
                      <td><?php echo $cr['total']; ?></td>
                      <td><button class="btn btn-danger delete-item" data-id="<?php echo $cr['id_cart']; ?>"><?php echo '<i class="fa fa-trash"></i>' ?></button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <?php if(mysqli_num_rows($cart) > 0) : ?>
                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-right">Subtotal:</td>
                      <td>Rp. <?php echo number_format($subtotal); ?></td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-right">Tax (5%):</td>
                      <td>Rp. <?php echo number_format($tax); ?></td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-right"><strong>Gross Total:</strong></td>
                      <td>Rp. <?php echo number_format($gross_total); ?></td>
                    </tr>
                  </tfoot>
                <?php endif; ?>
              </table>
              <form action="proses_trx.php" method="POST">
                <div class="form-group">
                  <label for="username">Username:</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo $usernameCashier; ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="notrx">Invoice No:</label>
                  <input type="text" class="form-control" id="notrx" name="notrx" value="<?php echo $number; ?>" readonly>
                </div>
                <input type="hidden" name="total" value="<?php echo $gross_total; ?>">
                <?php if(mysqli_num_rows($cart) > 0) : ?>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cutModal">Pay</button>
                <?php else : ?>
                  <p class="text-danger">There are no items in the cart.</p>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- Cut Modal -->
<div class="modal fade" id="cutModal" tabindex="-1" role="dialog" aria-labelledby="cutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cutModalLabel">Enter Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="cutForm">
  <div class="form-group">
    <label>Gross Total:</label>
    <p><?php echo 'Rp. ' . number_format($gross_total); ?></p>
  </div>
  <div class="form-group">
    <label for="cutValue">Cut Value:</label>
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text">Rp.</span>
      </div>
      <input type="number" class="form-control" id="cutValue" name="cutValue" min="<?php echo $gross_total; ?>" required>
    </div>
  </div>
  <input type="hidden" name="notrx" value="<?php echo $number; ?>">
  <input type="hidden" name="total" value="<?php echo $gross_total; ?>">
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="cutSubmit">Submit</button>
      </div>
    </div>
  </div>
</div>

  </main>
  <footer class="navbar-light py-3">
    <div class="container text-center">
      <span>&copy; 2023 Transaction page. By Steve.</span>
    </div>
  </footer>
  <script>
    // Display current time
    function displayClock() {
      var time = new Date().toLocaleTimeString();
      document.getElementById("time").innerHTML = time;
      setTimeout(displayClock, 1000); 
    }
    displayClock();

    // Display current day and year
    var d = new Date();
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var day = days[d.getDay()];
    var year = d.getFullYear();
    document.getElementById("day").innerHTML = day;
    document.getElementById("year").innerHTML = year;
  </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

      <script>
    // Delete item from cart
    $(document).on('click', '.delete-item', function() {
      var id_cart = $(this).data('id');
      $.ajax({
        url: 'deleteCartItem.php',
        type: 'POST',
        data: {id_cart: id_cart},
        success: function(data) {
          alert(data);
          location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
        }
      });
    });
    $(document).ready(function() {
        $('#vegetable, #quantity').on('keyup change', function() {
          if ($('#vegetable').val() != '' && $('#quantity').val() != '') {
            $('#addtocart').prop('disabled', false);
          } else {
            $('#addtocart').prop('disabled', true);
          }
        });
      });
  </script>
  <script>

$(document).ready(function() {
  $('#cutSubmit').on('click', function() {
    var cutValue = $('#cutValue').val();
    var grossTotal = <?php echo $gross_total; ?>;
    if(cutValue >= grossTotal) {
      $('#total').val(grossTotal - cutValue);
      $('#cutForm').submit();
    } else {
      alert('Cut value cannot be less than gross total.');
    }
  });
});


</script>
</body>
</html>