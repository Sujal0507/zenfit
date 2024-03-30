<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $product = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);

    // Check if product exists
    if (!$product) {
        // Redirect to manageproduct.php or show an error message
        header('Location: manageproduct.php');
        exit;
    }

    // Now you can use $product to populate your form for editing
    // For example:
    $title = $product['title'];
    $description = $product['description'];
    $price = $product['price'];
    $image = $product['image'];
} else {
    // Redirect to manageproduct.php or show an error message
    header('Location: manageproduct.php');
    exit;
}
?>

<?php include('adminnav.html'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST" action="updateproduct.php">
        <input type="hidden" name="id" value="<?php echo $productId; ?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $title; ?>"><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo $description; ?></textarea><br><br>
        
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo $price; ?>"><br><br>
        
        <label for="image">Image URL:</label><br>
        <input type="text" id="image" name="image" value="<?php echo $image; ?>"><br><br>
        
        <input type="submit" value="Update">
    </form>
</body>
</html>
