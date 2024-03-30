<?php
require_once 'db_connection.php';

function deleteProduct($collection, $productId) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    deleteProduct($collection, $_GET['id']);
    header('Location: manageproduct.php');
    exit;
}

$products = $collection->find();
?>
<?php include('adminnav.html');?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Product</title>
</head>
<body>
<link rel="stylesheet" href="css/manageproduct.css">

<h1>Manage Product</h1>
<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price (₹)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" width="100"></td>
                <td><?php echo $product['title']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td>₹<?php echo number_format($product['price'], 2); ?></td>
                <td>
    <a href="editproduct.php?id=<?php echo $product['_id']; ?>">Edit</a> |
    <a href="manageproduct.php?action=delete&id=<?php echo $product['_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
</td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="addproduct.php" target="_blank">Add New Product</a>
</body>
</html>
