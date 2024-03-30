<?php

require_once __DIR__ . '/vendor/autoload.php';
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");


$database = $mongoClient->selectDatabase("zenfit");
$collection = $database->selectCollection("blog");


$courseid=$_POST['id'];
$courseurl= $_POST['url'];
$courseTitle = $_POST['blogTitle'];
$courseDescription1 = $_POST['blogDescription1'];
$courseDescription2 = $_POST['blogDescription2'];



$document = [
    'id'=>$courseid,
    'image-url' => $courseurl,
    'title' => $courseTitle,
    'details-1' => $courseDescription1,
    'details-2' => $courseDescription2
];


$result = $collection->insertOne($document);

if ($result->getInsertedCount() > 0) {
    echo '<script> alert ("Blog added successfully!");</script>';
} else {
    echo '<script> alert("Error adding the blog."); </script>';
}

echo '<script> window.location.href = "add-blog.html";</script>';
?>
