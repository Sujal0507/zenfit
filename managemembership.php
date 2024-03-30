<?php
require_once __DIR__ . '/vendor/autoload.php';

// MongoDB connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Selecting a database
$database = $mongoClient->zenfit;

// Selecting a collection
$collection = $database->membership;

// Removing Existing Membership
if (isset($_POST['remove_membership'])) {
    $membershipName = $_POST['membership_name'];
    $deleteResult = $collection->deleteOne(['name' => $membershipName]);
    if ($deleteResult->getDeletedCount() > 0) {
        echo '<div class="alert alert-success" role="alert">Membership removed successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Failed to remove membership. Please try again.</div>';
    }
}

// Retrieving data from the collection
$cursor = $collection->find();
?>
<?php include('adminnav.html');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Membership</title>
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Membership Details</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Features</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursor as $membership): ?>
                    <tr>
                        <td><?php echo $membership['name']; ?></td>
                        <td><?php echo 'Rs ' . $membership['price']; ?></td>
                        <td><?php echo $membership['description']; ?></td>
                        <td>
                            <ul>
                                <?php foreach ($membership['features'] as $feature): ?>
                                    <li><?php echo $feature; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <form method="post" action="updatemembership.php">
                                <input type="hidden" name="membership_name" value="<?php echo $membership['name']; ?>">
                                <button type="submit" class="btn btn-primary" name="update_membership">Update</button>
                            </form>
                            <form method="post">
                                <input type="hidden" name="membership_name" value="<?php echo $membership['name']; ?>">
                                <button type="submit" class="btn btn-danger" name="remove_membership">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Membership Button -->
        <a href="addmembership.php" class="btn btn-success">Add Membership</a>
    </div>
</body>
</html>
