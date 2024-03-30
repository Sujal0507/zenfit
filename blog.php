<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog</title>
  <link rel="stylesheet" href="css/blog.css">
</head>
<body> 
<?php include('component/navbar.html'); ?>
    <div class="container-blog">
      <h1> Blogs</h1>
      <?php
      require_once __DIR__ . '/vendor/autoload.php';
      $client = new MongoDB\Client("mongodb://localhost:27017");
      $collection = $client->zenfit->blog;
      $cursor = $collection->find([]);

      foreach($cursor as $document) {
      echo '<div class="blog-post">';
       echo '<div class="img-blog">';
       echo '<img src="' .$document['image-url']. '">';
       echo '</div>';

        echo '<div class="content-blog">';
          echo '<div class="title-blog"><h2>'
          .$document['title'] .
           '<h2></div>';
          echo '<div class="body-blog">'
             .$document ['details-1']. 
           '</div>';  

          echo '<span class="read-more-link" id="read-more-link">Read More</span>';        
          echo '<div class="read-more-content" style="display:none">'
             .$document ['details-2']. 
          '</div>'; 
          echo '<span class="read-less-link">Read Less</span>';        
      echo '</div>'; 
      echo '</div>';
      }
      ?>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
  const readMoreLinks = document.querySelectorAll('.read-more-link');
  const readLessLinks = document.querySelectorAll('.read-less-link');
  
  readMoreLinks.forEach(function(link) {
    link.addEventListener('click', function() {
      const content = this.nextElementSibling;
      const readLessLink = this.parentElement.querySelector('.read-less-link');
      content.style.display = 'block';
      this.style.display = 'none';
      readLessLink.style.display = 'inline';
    });
  });

  readLessLinks.forEach(function(link) {
    link.addEventListener('click', function() {
      const content = this.previousElementSibling;
      const readMoreLink = this.parentElement.querySelector('.read-more-link');
      content.style.display = 'none';
      this.style.display = 'none';
      readMoreLink.style.display = 'inline';
    });
  });
});
    </script>
    <?php include('component/footers.html'); ?>
</body>
</html>
