<?php
require_once __DIR__ . '/vendor/autoload.php';

// MongoDB connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Selecting a database
$database = $mongoClient->zenfit;

// Selecting a collection
$collection = $database->membership;

// Adding New Membership
if (isset($_POST['add_membership'])) {
    $newMembership = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'description' => $_POST['description'],
        'features' => explode(',', $_POST['features'])
    ];

    $insertOneResult = $collection->insertOne($newMembership);
    if ($insertOneResult->getInsertedCount() > 0) {
        echo '<div class="alert alert-success" role="alert">Membership added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Failed to add membership. Please try again.</div>';
    }
}
?>

<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Membership</title>
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Membership</h2>
        <form method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="features">Features (comma-separated):</label>
                <input type="text" class="form-control" id="features" name="features" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_membership">Add Membership</button>
        </form>
    </div>
</body>
</html>
