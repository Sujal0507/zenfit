<?php
// Process form data for deleting an appointment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    require 'vendor/autoload.php'; // Include Composer's autoloader

    // MongoDB connection
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->zenfit->appointments;

    $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['delete_id'])]);
    if ($deleteResult->getDeletedCount() > 0) {
        echo "<script>alert('Appointment deleted successfully!');</script>";
        header("Location: manageappoint.php"); // Redirect to refresh the page
        exit; // Important to stop further script execution
    } else {
        echo "<script>alert('Error deleting appointment.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include('adminnav.html');?>
    <title>Manage Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .delete-btn {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'vendor/autoload.php'; // Include Composer's autoloader

                // MongoDB connection
                $client = new MongoDB\Client("mongodb://localhost:27017");
                $collection = $client->zenfit->appointments;

                // Fetch all appointments
                $cursor = $collection->find([]);

                // Display appointments
                if(isset($cursor) && !empty($cursor)):
                    foreach ($cursor as $appointment):
                ?>
                        <tr>
                            <td><?php echo $appointment['name']; ?></td>
                            <td><?php echo $appointment['email']; ?></td>
                            <td><?php echo $appointment['phone']; ?></td>
                            <td><?php echo $appointment['date']; ?></td>
                            <td><?php echo $appointment['time']; ?></td>
                            <td>
                                <form method="POST" action="manageappoint.php" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $appointment['_id']; ?>">
                                    <input type="submit" class="delete-btn" value="Delete">
                                </form>
                            </td>
                        </tr>
                <?php
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="6">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
