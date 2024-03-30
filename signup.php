<?php
require 'db.php';
require 'vendor/autoload.php'; // Path to autoload.php of PHP Mailer

// Start the session
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $joining_date = $_POST['joining_date'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $health_issues = $_POST['other'];

    // Validate Age
    if (!is_numeric($age) || $age < 16 || $age > 100) {
        $_SESSION['error_message'] = 'Age must be a number between 16 and 100.';
        header("Location: signup.php");
        exit();
    }

    // Validate Phone Number
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['error_message'] = 'Phone number must be a 10-digit number.';
        header("Location: signup.php");
        exit();
    }

    // Validate Date of Joining
    $today = date("Y-m-d");
    if ($joining_date > $today) {
        $_SESSION['error_message'] = 'Date of Joining cannot be a future date.';
        header("Location: signup.php");
        exit();
    }

    $insertResult = $collection->insertOne([
        'name' => $name,
        'age' => $age,
        'gender' => $gender,
        'phone' => $phone,
        'joining_date' => $joining_date,
        'email' => $email,
        'password' => $password,
        'health_issues' => $health_issues
    ]);

    if ($insertResult->getInsertedCount() > 0) {
        // Send Welcome Email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'zenfit.india@gmail.com';                     // SMTP username
            $mail->Password   = '';                        // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('your@example.com', 'Zenfit India Pvt Ltd');
            $mail->addAddress($email, $name);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Welcome , ' .$name. ' to Zenfit Fitness!';
            $mail->Body    =  '<h1><b><span style="color: red;">Welcome to Zenfit Fitness' .$name.'</span></b></h1>

        <br>We are here to help you achieve your fitness goals. If you have any questions or need assistance, feel free to reach out.<br>
        <p>Follow us on instagram: @zenfitindia and
        Also on X: @Sujalpatel788 </p>';

            $mail->send();
            $_SESSION['success_message'] = 'User Registered Successfully!';
            echo "<script>alert('User successfully registered'); window.location.href = 'signin.php';</script>";
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Registration successful! Welcome email could not be sent. Please contact support.';
            header("Location: signup.php");
            exit();
        }
    }
}

// Display error message in alert box if present
if (isset($_SESSION['error_message'])) {
    echo "<script>alert('{$_SESSION['error_message']}'); window.location.href = 'signup.php';</script>";
    unset($_SESSION['error_message']); // Clear the session variable
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ZenFit Signup</title> 
  <link rel="stylesheet" href="css/signup.css">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link rel="icon" href="assets/favicon.png" type="image/png" sizes="32x32">
  <link rel="icon" href="assets/favicon-192.png" type="image/png" sizes="192x192">
  <script>
    // JavaScript to disable future dates in the Date of Joining field
    window.onload = function() {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1; // January is 0!
      var yyyy = today.getFullYear();

      if (dd < 10) {
        dd = '0' + dd;
      }

      if (mm < 10) {
        mm = '0' + mm;
      }

      today = yyyy + '-' + mm + '-' + dd;
      document.getElementById('joining_date').setAttribute('max', today);
    };
  </script>
</head>
<body>
  <div class="wrapper">
    <h1>Signup for ZenFit</h1>
    <form action="signup.php" method="POST" onsubmit="return validateForm()">
      <!-- Your form fields -->
      <div class="form-group">
        <label for="name">Full Name:</label>
        <div class="input-box">
          <input type="text" id="name" name="name" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="age">Age:</label>
        <div class="input-box">
          <input type="text" id="age" name="age" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="gender">Select Gender:</label>
        <div class="input-box">
          <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      
      <div class="form-group">
        <label for="phone">Contact Number:</label>
        <div class="input-box">
          <input type="number" id="phone" name="phone" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="joining_date">Date of Joining:</label>
        <div class="input-box">
          <input type="date" id="joining_date" name="joining_date" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="email">Email:</label>
        <div class="input-box">
          <input type="email" id="email" name="email" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="password">Create Password:</label>
        <div class="input-box">
          <input type="password" id="password" name="password" required>
        </div>
      </div>

      <div class="form-group">
        <label for="other">Do you have any health issues? (Write No if None)</label>
        <div class="input-box">
          <input type="text" id="other" name="other" required>
        </div>
      </div>
      
      <center>
        <div class="form-group button">
          <input type="submit" value="Register Now">
        </div>
      </center>
      
      <div class="text">
        <h3>Already have an account? <a href="signin.php">Sign In</a></h3>
      </div>
    </form>
    
    <?php
    // Display success message if present
    if (isset($_SESSION['success_message'])) {
        echo "<script>alert('User successfully registered'); window.location.href = 'signin.php';</script>";
        unset($_SESSION['success_message']); // Clear the session variable
    }
    ?>
  </div>
  
  <!-- Display error message in alert box if present -->
  <?php if (isset($_SESSION['error_message'])): ?>
    <script>
      alert('<?php echo $_SESSION['error_message']; ?>');
      <?php unset($_SESSION['error_message']); // Clear the session variable after showing alert ?>
    </script>
  <?php endif; ?>
  
  <script>
    // JavaScript function to validate the form before submission
    function validateForm() {
      var age = document.getElementById('age').value;
      var phone = document.getElementById('phone').value;
      var joiningDate = document.getElementById('joining_date').value;
      
      if (isNaN(age) || age < 16 || age > 100) {
        alert('Age must be a number between 16 and 100.');
        return false;
      }
      
      if (!/^[0-9]{10}$/.test(phone)) {
        alert('Phone number must be a 10-digit number.');
        return false;
      }
      
      var today = new Date();
      var selectedDate = new Date(joiningDate);
      
      if (selectedDate > today) {
        alert('Date of Joining cannot be a future date.');
        return false;
      }
      
      return true;
    }
  </script>
</body>
</html>

