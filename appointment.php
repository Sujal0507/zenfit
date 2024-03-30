<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// MongoDB connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->zenfit->appointments;

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Insert data into MongoDB
    $result = $collection->insertOne([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'date' => $date,
        'time' => $time
    ]);

    if ($result->getInsertedCount() > 0) {
        // Appointment booked successfully, show JavaScript alert
        echo "<script>alert('Appointment booked successfully!');</script>";
    } else {
        // Error booking appointment, show JavaScript alert
        echo "<script>alert('Error booking appointment.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
<?php include('component/navbar.html'); ?>
    <title>Make an Appointment</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&display=swap">
    <style>
        body {
            font-family: "Cinzel", serif;
            margin: 0;
           
            background: url('/assets/appoin.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            padding: 20px;
            max-width: 600px;
            margin-top: 15px;
            background: rgb(0 0 0 / 80%);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color:#c11325;
            font-family: "Cinzel", serif;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: whitesmoke;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            
            background-size: 15px;
            padding-right: 30px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #c11325;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Montserrat', sans-serif;
        }

        input[type="submit"]:hover {
            background-color: gray;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Appointment</h1>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label for="time">Time Slots:</label>
                <select id="time" name="time" required>
                    <option value="10am-1pm">10am - 1pm</option>
                    <option value="3pm-5pm">3pm - 5pm</option>
                    <option value="6pm-8pm">6pm - 8pm</option>
                </select>
            </div>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
