<?php
session_start();

// Include the MongoDB PHP library (make sure you have it installed)
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

// MongoDB connection setup
$mongoClient = new MongoClient("mongodb://localhost:27017");
$db = $mongoClient->selectDatabase('zenfit');
$collection = $db->selectCollection('bills');

// Query MongoDB to fetch all bills
$billsCursor = $collection->find();

// Convert cursor to array
$billsArray = iterator_to_array($billsCursor);
?>

<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Generated | Zenfit</title>
    <link rel="stylesheet" href="css/bill.css">
</head>
<body>
    <h1>Bill Generated</h1>
    <table>
        <thead>
            <tr>
                <th>Bill ID</th>
                <th>Username</th>
                <th>Total</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($billsArray as $bill) { ?>
                <tr>
                    <td><?= $bill['_id'] ?></td>
                    <td><?= $bill['name'] ?></td>
                    <td>₹ <?= $bill['total'] ?></td>
                    <td><?= date('Y-m-d H:i:s', $bill['timestamp']->toDateTime()->getTimestamp()) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
