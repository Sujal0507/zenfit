<?php
require 'db.php';

// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: signin.php");
    exit();
}

// Fetching name and email from session
$userName = $_SESSION['name'];
$userEmail = $_SESSION['email'];

// Alert message
$alertMessage = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $membership = $_POST['membership'];
    $other = $_POST['other'];

    // MongoDB connection
    require 'vendor/autoload.php'; // Composer's autoloader
    $client = new MongoDB\Client('mongodb://localhost:27017');
    $collection = $client->zenfit->members;

    // Prepare document to insert
    $document = [
        'name' => $name,
        'email' => $email,
        'membership' => $membership,
        'other' => $other,
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ];

    // Insert document into MongoDB
    $insertResult = $collection->insertOne($document);

    if ($insertResult->getInsertedCount() === 1) {
        // Success message
        $alertMessage = 'Now you\'re a Zenfit member!';
    } else {
        // Error message
        $alertMessage = 'Something went wrong. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Form</title>
    <link rel="stylesheet" href="/css/styles.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@500;600;700&family=Nunito:wght@300;400&family=Roboto:wght@300&display=swap" rel="stylesheet">
</head>
<body>

<div class="image">
    <img src="assets/membership-pic.jpg">
</div>

<div class="container">
    <a href="home.php"><i class="fa fa-times fa-lg"  aria-hidden="true"></i></a>
    <h2>Zenfit Membership Plan</h2>
    <form id="membershipForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userName); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" required>
        </div>
        <div class="form-group">
            <label for="membership">Select Membership:</label>
            <select id="membership" name="membership" required>
                <option value="zenfit_go">Zenfit Go</option>
                <option value="zenfit_pro">Zenfit Pro</option>
                <option value="zenfit_plus">Zenfit Plus</option>
            </select>
        </div>
        <div class="form-group">
            <label for="other">Any Specific Requirement (Write No if Not)</label>
            <input type="text" id="other" name="other" required>
        </div>
        <button type="submit">Join Now</button>
    </form>
</div>

<script src="app.js"></script>
<?php if (!empty($alertMessage)) : ?>
    <script>
        alert("<?php echo $alertMessage; ?>");
    </script>
<?php endif; ?>
</body>
</html>
