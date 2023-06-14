<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Project</title>
    <style>
      body {
        background-color: #f2f2f2;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
      }

      .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      h1 {
        font-size: 3rem;
        text-align: center;
        margin-top: 0;
      }

      .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 3rem;
      }

      .btn {
        padding: 1rem 2rem;
        font-size: 1.2rem;
        border-radius: 5px;
        border: none;
        color: #fff;
        background-color: #2d4059;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .btn:hover {
        background-color: #1c2938;
      }

      h3 {
        font-size: 1.2rem;
        text-align: center;
        margin-top: 2rem;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Welcome to My Cashier Project</h1>
      <div class="btn-container">
        <form action="admin.php" method="post" enctype="multipart/form-data">
          <button type="submit" class="btn" name="admin">Admin Page</button>
        </form>
        <form action="cashier.php" method="post" enctype="multipart/form-data">
          <button type="submit" class="btn" name="cashier">Cashier Page</button>
        </form>
      </div>
      <h3>Made by the Owner</h3>
    </div>
  </body>
</html>
