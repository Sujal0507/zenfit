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

// Calculate monthly sales for each user
$monthlySales = [];

foreach ($billsArray as $bill) {
    $username = $bill['name'];
    $total = $bill['total'];

    if (!isset($monthlySales[$username])) {
        $monthlySales[$username] = 0;
    }

    $monthlySales[$username] += $total;
}
?>

<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report According to Customer (With Amount) | Zenfit</title>
    <link rel="stylesheet" href="css/bill.css">
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Sales Report According to Customer (With Amount)</h1>
    <center>
    <div class="chart-container">
        <canvas id="monthlySalesPieChart" width="400" height="400"></canvas>
    </div>
</center>
    <!-- JavaScript for Charts -->
    <script>
        // Pie Chart
        var pieData = {
            labels: <?= json_encode(array_keys($monthlySales)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($monthlySales)) ?>,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        };

        var pieOptions = {
            responsive: false,
            maintainAspectRatio: false
        };

        var pieChartCanvas = document.getElementById('monthlySalesPieChart').getContext('2d');
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        });
    </script>
</body>
</html>
