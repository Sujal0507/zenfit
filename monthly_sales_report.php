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

// Calculate daily sales
$dailySales = [];

foreach ($billsArray as $bill) {
    $timestamp = $bill['timestamp']->toDateTime()->format('Y-m-d');
    $total = $bill['total'];

    if (!isset($dailySales[$timestamp])) {
        $dailySales[$timestamp] = 0;
    }

    $dailySales[$timestamp] += $total;
}
?>

<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Sales Report | Zenfit</title>
    <link rel="stylesheet" href="css/bill.css">
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Monthly Sales Report</h1>
    <center>
    <div class="chart-container">
        <canvas id="dailySalesBarChart" width="400" height="400"></canvas>
    </div>
    </center>
    <!-- JavaScript for Charts -->
    <script>
        // Bar Chart
        var barData = {
            labels: <?= json_encode(array_keys($dailySales)) ?>,
            datasets: [{
                label: 'Daily Sales',
                data: <?= json_encode(array_values($dailySales)) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var barOptions = {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var barChartCanvas = document.getElementById('dailySalesBarChart').getContext('2d');
        new Chart(barChartCanvas, {
            type: 'bar',
            data: barData,
            options: barOptions
        });
    </script>
</body>
</html>
