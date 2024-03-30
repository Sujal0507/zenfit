<?php
require_once __DIR__ . '/vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->zenfit->blog;

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    // Retrieve the product details based on the _id
    $product = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);
    
    if (!$product) {
        echo "Blog not found.";
        exit;
    }
    
    // Handle form submission for updating the product
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated product data from the form
        $updatedProduct = [
            'image-url' => $_POST['image-url'],
            'title' => $_POST['title'],
            'details-1' => $_POST['details-1'],
            'details-2' => $_POST['details-2'],
            // Add other fields as needed
        ];
        
        // Update the product in the database
        $result = $collection->updateOne(['_id' => new MongoDB\BSON\ObjectId($productId)], ['$set' => $updatedProduct]);
        
        if ($result->getModifiedCount() > 0) {
            header('Location: manageblog.php');
            exit;
        } else {
            echo "Failed to update the Blog.";
        }
    }
} else {
    echo "Blog ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Blog</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
        }

        .edit-form-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .edit-form-container h1 {
            text-align: center;
        }

        .edit-form-container label {
            display: block;
            margin-top: 10px;
        }

        .edit-form-container input[type="text"],
        .edit-form-container textarea,
        .edit-form-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-form-container input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .edit-form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include('adminnav.html'); ?>
<div class="edit-form-container">
    <h1>Edit Blog</h1>
    <form method="POST">
        <label for="image-url">Image URL:</label>
        <input type="text" id="image-url" name="image-url" value="<?php echo $product['image-url']; ?>" required><br>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $product['title']; ?>" required><br>
        
        <label for="details-1">Details 1:</label>
        <textarea id="details-1" name="details-1" required><?php echo $product['details-1']; ?></textarea><br>
        
        <label for="details-2">Details 2:</label>
        <textarea id="details-2" name="details-2" required><?php echo $product['details-2']; ?></textarea><br>
        
        <!-- Add fields for other product details as needed -->
        
        <input type="submit" value="Update Blog">
    </form>
</div>
</body>
</html>
