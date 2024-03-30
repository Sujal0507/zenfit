<?php
// Include the MongoDB PHP library (make sure you have it installed)
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

// Set the timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

// MongoDB connection setup
$mongoClient = new MongoClient("mongodb://localhost:27017");
$db = $mongoClient->selectDatabase('zenfit');
$collection = $db->selectCollection('products');

// Function to calculate margin
function calculateMargin($amount) {
    if ($amount > 3000) {
        // 20% margin for products over 3000
        return $amount * 0.20;
    } else {
        // 15% margin for products under or equal to 3000
        return $amount * 0.15;
    }
}

// Query MongoDB to fetch all products
$productsCursor = $collection->find();

// Calculate margin for each product
$productsMargins = [];

foreach ($productsCursor as $product) {
    $productName = $product['title'];
    $productTotal = (int)$product['price']; // Assuming 'price' field stores the total amount of each product

    // Calculate margin for the product
    $margin = calculateMargin($productTotal);

    // Store product name and margin in array
    $productsMargins[] = [
        'name' => $productName,
        'total' => $productTotal,
        'margin' => $margin
    ];
}

// Sort products by margin in descending order
usort($productsMargins, function($a, $b) {
    return $b['margin'] <=> $a['margin'];
});

// Limit the number of products to display
$numberOfProductsToShow = 10;
$topProducts = array_slice($productsMargins, 0, $numberOfProductsToShow);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Margins Report | Zenfit</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        td:first-child {
            font-weight: bold;
        }

        td:last-child {
            font-family: 'Courier New', Courier, monospace;
            color: #28a745;
            font-weight: bold;
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body style="
    padding-top: 0px;
    padding-left: 0px;
    padding-right: 0px;
">
<?php include('adminnav.html');?>
    <h1>Product Margins Report</h1>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Amount</th>
                <th>Margin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topProducts as $product) { ?>
                <tr>
                    <td><?= $product['name'] ?></td>
                    <td>₹ <?= $product['total'] ?></td>
                    <td>₹ <?= $product['margin'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
