<?php
require 'vendor/autoload.php'; // Composer's autoloader

// MongoDB connection
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection = $client->zenfit->attendance;
$usersCollection = $client->zenfit->users; // New collection for users

// Initialize alert message
$alertMessage = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $attendanceDate = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Check if entry already exists for the same member on the same date
    $existingEntry = $collection->findOne([
        'name' => $name,
        'attendance_date' => new MongoDB\BSON\UTCDateTime(strtotime($attendanceDate) * 1000)
    ]);

    if ($existingEntry) {
        // If entry exists, update the status
        $updateResult = $collection->updateOne(
            ['_id' => $existingEntry['_id']],
            ['$set' => ['status' => $status]]
        );

        if ($updateResult->getModifiedCount() === 1) {
            $alertMessage = 'Attendance updated successfully!';
        } else {
            $alertMessage = 'Failed to update attendance. Please try again.';
        }
    } else {
        // Prepare new document to insert
        $document = [
            'name' => $name,
            'attendance_date' => new MongoDB\BSON\UTCDateTime(strtotime($attendanceDate) * 1000),
            'status' => $status,
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ];

        // Insert new document into MongoDB
        $insertResult = $collection->insertOne($document);

        if ($insertResult->getInsertedCount() === 1) {
            $alertMessage = 'Attendance recorded successfully!';
        } else {
            $alertMessage = 'Failed to record attendance. Please try again.';
        }
    }

    // JavaScript to show alert after form submission
    echo '<script>';
    echo 'alert("' . $alertMessage . '");';
    echo '</script>';
}

// Retrieve member names from MongoDB for dropdown
$members = $usersCollection->find([], ['projection' => ['name' => 1]]);
$memberNames = [];
foreach ($members as $member) {
    $memberNames[] = $member['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
      /* Resetting default browser styles */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

h1 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.form-container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: 500;
    margin-bottom: 5px;
}

select,
input[type="date"],
button {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="%23333" d="M10 12L4 6h12z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 20px;
    padding-right: 40px;
}

button {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

.attendance-history {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 20px;
}

.attendance-history h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ccc;
}

table th {
    background-color: #007bff;
    color: #fff;
}

table td {
    text-align: center;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.alert-success {
    background-color: #28a745;
    color: #fff;
}

.alert-danger {
    background-color: #dc3545;
    color: #fff;
}

    </style>
<body style="
    padding-top: 0px;
    padding-left: 0px;
    padding-right: 0px;
">
<?php include('trainernav.html');?>
<div class="container">
    <h1>Attendance System</h1>
    <div class="form-container">
        <form id="attendanceForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="name">Member Name:</label>
                <select id="name" name="name" required>
                    <option value="">Select Member</option>
                    <?php foreach ($memberNames as $memberName) : ?>
                        <option value="<?php echo $memberName; ?>"><?php echo $memberName; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="attendance_date">Attendance Date:</label>
                <input type="date" id="attendance_date" name="attendance_date" required>
            </div>
            <div class="form-group">
                <label for="status">Attendance Status:</label>
                <select id="status" name="status" required>
                <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php
    if (isset($_POST['name'])) {
        $selectedMember = $_POST['name'];
        $memberHistory = $collection->find(['name' => $selectedMember]);
        ?>
        <div class="attendance-history">
            <h2>Attendance History for <?php echo $selectedMember; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($memberHistory as $history) : ?>
                        <tr>
                            <td><?php echo date('M d, Y', $history['attendance_date']->toDateTime()->getTimestamp()); ?></td>
                            <td><?php echo $history['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
</body>
</html>

