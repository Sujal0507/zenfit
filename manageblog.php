<?php
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->zenfit;
$collection = $db->blog;

function deleteProduct($collection, $productId) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    deleteProduct($collection, $_GET['id']);
    header('Location: manageblog.php');
    exit;
}

$products = $collection->find();
?>
<?php include('adminnav.html');?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Blog</title>
</head>
<body>
<link rel="stylesheet" href="css/manageproduct.css">

<h1>Manage Blog</h1>
<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Details-1</th>
            <th>Details-2</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="<?php echo $product['image-url']; ?>" alt="<?php echo $product['title']; ?>" width="100"></td>
                <td><?php echo $product['title']; ?></td>
                <td><?php echo $product['details-1']; ?></td>
                <td><?php echo $product['details-2']; ?></td>

                   <td> <a href="editblog.php?id=<?php echo $product['_id']; ?>">Edit</a> |
                    <a href="manageblog.php?action=delete&id=<?php echo $product['_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="add-blog.html" target="_blank">Add New Blog</a>
</body>
</html>
