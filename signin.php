<?php
require 'db.php';

// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $collection->findOne(['email' => $email]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Invalid email or password';
        header("Location: signin.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZenFit Sign In</title> 
  <link rel="stylesheet" href="css/signin.css">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link rel="icon" href="assets/favicon.png" type="image/png" sizes="32x32">
  <link rel="icon" href="assets/favicon-192.png" type="image/png" sizes="192x192">
  <?php
  // Check if error message is set and if so, output JavaScript alert
  if (isset($_SESSION['error_message'])) {
      echo '<script>';
      echo 'window.onload = function() {';
      echo 'alert("' . $_SESSION['error_message'] . '");';
      echo '}';
      echo '</script>';
      unset($_SESSION['error_message']); // Clear the session variable
  }
  ?>
</head>
<body>
  <div class="wrapper">
    <h2>Sign In to ZenFit</h2>
    <form action="signin.php" method="POST">
    <div class="form-group">
        <label for="email">Email:</label>
        <div class="input-box">
          <input type="email" id="email" name="email" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="password">Password:</label>
        <div class="input-box">
          <input type="password" id="password" name="password" required>
        </div>
      </div>
      <div class="form-group button">
        <input type="submit" value="Login">
      </div>
      <div class="text">
        <h3>Don't have an account? <a href="signup.php">Sign up now</a></h3>
      </div>
    </form>
  </div>
</body>
</html>
