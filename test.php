<!DOCTYPE html>
<html>
<head>
<?php include('trainernav.html');?>
    <title>Customer Products Table</title>
    <center><h1>Customer's Order History</h1></center>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            font-family: 'Cinzel', serif;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        td {
            background-color: #f2f2f2;
        }

        

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        @media screen and (max-width: 600px) {
            table {
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// MongoDB connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongoClient->zenfit->bills;

// Aggregate query to get customers, products, quantities, and total amount
$cursor = $collection->aggregate([
    [
        '$project' => [
            'name' => 1,
            'products' => [
                '$objectToArray' => '$cart'
            ],
            'total' => 1
        ]
    ],
    [
        '$unwind' => '$products'
    ],
    [
        '$match' => [
            'products.v.quantity' => ['$gt' => 0] // Filter out products with quantity 0
        ]
    ],
    [
        '$group' => [
            '_id' => [
                'name' => '$name',
                'product' => '$products.v.title'
            ],
            'totalQuantity' => ['$sum' => '$products.v.quantity'],
            'totalAmount' => ['$first' => '$total']
        ]
    ]
]);

// Associative array to store customer products and quantities
$customerProducts = [];

// Iterate through the cursor and populate the associative array
foreach ($cursor as $document) {
    $name = $document['_id']['name'];
    $product = $document['_id']['product'];
    $quantity = $document['totalQuantity'];
    $totalAmount = $document['totalAmount'];

    // If customer exists in the array, update quantity
    if (isset($customerProducts[$name][$product])) {
        $customerProducts[$name][$product]['quantity'] += $quantity;
    } else {
        // If customer does not exist, add new entry
        $customerProducts[$name][$product] = [
            'quantity' => $quantity,
            'totalAmount' => $totalAmount
        ];
    }
}

// Table header
echo "<table>";
echo "<tr><th>Name</th><th>Product</th><th>Quantity</th><th>Total Amount</th></tr>";

// Table rows for each customer, product, quantity, and total amount
foreach ($customerProducts as $customerName => $products) {
    $firstRow = true;
    foreach ($products as $product => $data) {
        echo "<tr>";
        // Display the customer name only for the first row of the customer
        if ($firstRow) {
            echo "<td rowspan='" . count($products) . "'>" . $customerName . "</td>";
            $firstRow = false;
        }
        echo "<td>" . $product . "</td>";
        echo "<td>" . $data['quantity'] . "</td>";
        echo "<td>₹" . $data['totalAmount'] . "</td>";
        echo "</tr>";
    }
}

echo "</table>";
?>

</body>
</html>
