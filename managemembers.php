<?php
// MongoDB connection
require 'vendor/autoload.php'; // Composer's autoloader
$client = new MongoDB\Client('mongodb://localhost:27017');
$collection = $client->zenfit->members;

// Fetch all member data from the collection
$cursor = $collection->find();

// Check if any members found
$membersExist = false;
foreach ($cursor as $document) {
    $membersExist = true;
    break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-info {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
  <?php include('adminnav.html');?>

<div class="container">
    <h2>Member List</h2>
    <?php if (!$membersExist) : ?>
        <div class="alert alert-info" role="alert">
            No members found.
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Membership Plan</th>
                        <th>Specific Requirement</th>
                        <th>Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursor as $document) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($document['name']); ?></td>
                            <td><?php echo htmlspecialchars($document['email']); ?></td>
                            <td><?php echo htmlspecialchars($document['membership']); ?></td>
                            <td><?php echo htmlspecialchars($document['other']); ?></td>
                            <td><?php echo date('F j, Y, g:i a', $document['timestamp']->toDateTime()->getTimestamp()); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
