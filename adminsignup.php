<?php
require_once __DIR__ . '/vendor/autoload.php';?>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

   
    $client = new MongoDB\Client("mongodb://localhost:27017");

    $db = $client->zenfit;
    $collection = $db->admin;

    
    $existingUser = $collection->findOne(["email" => $email]);

    if ($existingUser) {
        echo "<script>alert('Email already exists');</script>";
    } else {
        
        $userDocument = [
            "name" => $name,
            "email" => $email,
            "password" => $hashedPassword
        ];

       
        $result = $collection->insertOne($userDocument);

        if ($result->getInsertedCount() > 0) {
            echo "<script>alert('Admin registered successfully');</script>";
        } else {
            echo "<script>alert('Registration failed');</script>";
        }
    }
}
?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Zenfit Signup </title> 
    <link rel="stylesheet" href="css\asignup.css">
   </head>
<body>
  <div class="wrapper">

    <h2>Signup for Zenfit Admin</h2>
    <form action="adminsignup.php" method="POST">
      <div class="input-box">
        <input type="text" name="name" placeholder="Enter your name" required >
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="Enter your email" required >
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Create password" required>
      </div>
    
      <!-- <div class="policy">
        <input type="checkbox">
        <h3>I accept all terms & condition</h3>
      </div> -->
      <div class="input-box button">
        <input type="Submit" value="Register Now">
      </div>
      <div class="text">
        <h3>Already a Admin? <a href="alogin.php">Login now</a></h3>
      </div>
    </form>
  </div>
  
</body>
</html>
