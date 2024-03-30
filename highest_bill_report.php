<?php
// Include the MongoDB PHP library (make sure you have it installed)
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

// MongoDB connection setup
$mongoClient = new MongoClient("mongodb://localhost:27017");
$db = $mongoClient->selectDatabase('zenfit');
$collection = $db->selectCollection('bills');

// Query MongoDB to find the customer with the highest bill amount
$highestBill = $collection->findOne([], ['sort' => ['total' => -1]]);

// Extract customer information
$customerName = $highestBill['name'];
$highestAmount = $highestBill['total'];

// HTML for the report page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highest Bill Report | Zenfit</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .report-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .customer-info {
            background-color: #f9f9f9;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .customer-info p {
            font-size: 18px;
            margin: 0;
            padding: 10px 0;
            color: #555;
        }

        .customer-info strong {
            font-weight: bold;
            color: #333;
        }

        .btn-back {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 24px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 768px) {
            .report-container {
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            .customer-info {
                padding: 15px;
            }

            .customer-info p {
                font-size: 16px;
            }

            .btn-back {
                font-size: 16px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body style="padding-top: 0px;padding-left: 0px;padding-right: 0px;">
<?php include('adminnav.html');?>
    <div class="report-container">
        <h1>Highest Bill Amount Customer</h1>
        <div class="customer-info">
            <p><strong>Customer Name:</strong> <?= $customerName ?></p>
            <p><strong>Highest Bill Amount:</strong> ₹ <?= $highestAmount ?></p>
        </div>
        <a href="index.php" class="btn-back">Back to Dashboard</a>
    </div>
</body>
</html>
