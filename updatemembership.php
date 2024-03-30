<?php
require_once __DIR__ . '/vendor/autoload.php';

// MongoDB connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Selecting a database
$database = $mongoClient->zenfit;

// Selecting a collection
$collection = $database->membership;

if (isset($_POST['update_membership'])) {
    $membershipName = $_POST['membership_name'];
    
    // Fetch membership details to pre-fill the form
    $membership = $collection->findOne(['name' => $membershipName]);

    if ($membership) {
        // Convert BSONArray to PHP array for implode
        $featuresArray = is_array($membership['features']) ? $membership['features'] : $membership['features']->getArrayCopy();

        // Display the update form with pre-filled values
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Update Membership</title>
            <!-- Bootstrap CSS Link -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container mt-5">
                <h2>Update Membership</h2>
                <form method="post" id="update_membership_form">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $membership['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" class="form-control" id="price" name="price" value="<?php echo $membership['price']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required><?php echo $membership['description']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="features">Features (comma-separated):</label>
                        <input type="text" class="form-control" id="features" name="features" value="<?php echo implode(',', $featuresArray); ?>" required>
                    </div>
                    <input type="hidden" name="membership_name" value="<?php echo $membership['name']; ?>">
                    <button type="submit" class="btn btn-primary" name="update_membership_submit">Update Membership</button>
                </form>
            </div>
        </body>
        </html>
        <script>
            document.getElementById('update_membership_form').addEventListener('submit', function() {
                alert('Form submitted successfully!');
            });
        </script>
        <?php
    } else {
        echo '<div class="alert alert-danger" role="alert">Membership not found.</div>';
    }
}

// Handle the form submission to update membership
if (isset($_POST['update_membership_submit'])) {
    $membershipName = $_POST['membership_name'];
    
    // Update the membership details
    $updateResult = $collection->updateOne(
        ['name' => $membershipName],
        [
            '$set' => [
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'description' => $_POST['description'],
                'features' => explode(',', $_POST['features'])
            ]
        ]
    );

    if ($updateResult->getModifiedCount() > 0) {
        echo '<script>alert("Membership updated successfully!");</script>';
    } else {
        echo '<script>alert("Failed to update membership. Please try again.");</script>';
    }
}
?>
