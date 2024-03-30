<?php
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

// Calculate total sales for each product
$productSales = [];

foreach ($billsArray as $bill) {
    $cart = $bill['cart'];

    foreach ($cart as $item) {
        $productName = $item['title'];
        $productPrice = $item['price'];
        $productQuantity = $item['quantity'];
        $productTotal = $productPrice * $productQuantity;

        if (!isset($productSales[$productName])) {
            $productSales[$productName] = 0;
        }

        $productSales[$productName] += $productTotal;
    }
}

// Sort products by total sales (descending order)
arsort($productSales);

// Get top 3 products
$topProducts = array_slice($productSales, 0, 3, true);

// Now $topProducts contains the top 3 highest selling products

// Display the results
?>
<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Selling Products | Zenfit</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #444;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 36px;
        }

        .product-list {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        td:first-child {
            font-weight: bold;
            color: #007bff;
            font-size: 18px; /* Larger font size */
        }

        td:nth-child(2) {
            font-weight: bold;
            color: #28a745;
        }

        /* Unique hover effect for the first row */
        tr.first-row {
            background-color: #ffc107;
            transition: background-color 0.3s ease;
        }

        tr.first-row:hover {
            background-color: #ffc107; /* Keep the same color on hover */
        }

        tr.first-row:hover td {
            color: #fff;
        }

        tr.first-row td {
            font-size: 20px; /* Larger font size for the first row */
            font-weight: bold;
            color: #dc3545; /* Different color for the first row */
        }

        tr:not(.first-row):hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body style="
    padding-top: 0px;
    padding-left: 0px;
    padding-right: 0px;
">
    <div class="container">
        <h1>Top 3 Highest Selling Products</h1>

        <div class="product-list">
            <table>
                <thead>
                    <tr>
                        <th>#</th> <!-- Numbering column -->
                        <th>Product Name</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 1; // Initialize a counter
                    foreach ($topProducts as $productName => $totalSales) { ?>
                        <?php $class = ($count === 1) ? 'first-row' : ''; ?>
                        <tr class="<?= $class ?>">
                            <td><?= $count++ ?></td> <!-- Increment the counter -->
                            <td><?= $productName ?></td>
                            <td>₹ <?= $totalSales ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
