
<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: signin.php");
    exit();
}

// Fetch the username from the session
$user = $_SESSION['name'];


include('component\navbar.html');

?>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
<link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/css/flaticon.css" type="text/css">
    <link rel="stylesheet" href="/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="/css/barfiller.css" type="text/css">
    <link rel="stylesheet" href="/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="/css/style.css" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<link rel="stylesheet" href="swiper.css">
<link rel="stylesheet" href="/css/choose.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
<link rel="stylesheet" href="/css/animate.css">
  <link rel="stylesheet" href="/css/hero.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <!-- Start Home -->
<section class="home wow flash" id="home">

    <div class="container" style="
    padding-left: 0px;
">
      <h1 class="wow slideInLeft" data-wow-delay="1s">It's <span>Zenfit</span> Era.Hey <?php echo $user ?>,</h1>
      <h1 class="wow slideInRight" data-wow-delay="1s">We're Ready <span>fit you</span></h1>
  </div>
  <!-- go down -->
  <a href="#about" class="go-down">
      <i class="fa fa-angle-down" aria-hidden="true"></i>
  </a>
  <!-- go down -->

</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  $(document).ready(function () {

      $(".ham-burger, .nav ul li a").click(function () {

          $(".nav").toggleClass("open")

          $(".ham-burger").toggleClass("active");
      })
      $(".accordian-container").click(function () {
          $(".accordian-container").children(".body").slideUp();
          $(".accordian-container").removeClass("active")
          $(".accordian-container").children(".head").children("span").removeClass("fa-angle-down").addClass("fa-angle-up")
          $(this).children(".body").slideDown();
          $(this).addClass("active")
          $(this).children(".head").children("span").removeClass("fa-angle-up").addClass("fa-angle-down")
      })

      $(".nav ul li a, .go-down").click(function (event) {
          if (this.hash !== "") {

              event.preventDefault();

              var hash = this.hash;

              $('html,body').animate({
                  scrollTop: $(hash).offset().top
              }, 800, function () {
                  window.location.hash = hash;
              });

              // add active class in navigation
              $(".nav ul li a").removeClass("active")
              $(this).addClass("active")
          }
      })
  })

</script>
<script src="min.js"></script>
<script>
  wow = new WOW(
      {
          animateClass: 'animated',
          offset: 0,
      }
  );
  wow.init();
</script>
<!-- End Home -->
</body>
</html>
  
   <!-- Banner Section Begin -->
   <section class="banner-section set-bg" data-setbg="/assets/bg2.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="bs-text">
                        <h2>Zenfit - All in One Destination</h2>
                        <div class="bt-tips">Where health, beauty and fitness meet.</div>
                        <a href="/appointment.php" class="primary-btn  btn-normal">Appointment</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->
      <!-- Pricing Section Begin -->
      <section class="pricing-section spad">
        <?php
        require_once __DIR__ . '/vendor/autoload.php';

        // MongoDB connection
        $mongoClient = new MongoDB\Client("mongodb://localhost:27017");

        // Selecting a database
        $database = $mongoClient->zenfit;

        // Selecting a collection
        $collection = $database->membership;

        // Retrieving data from the collection
        $cursor = $collection->find();
        ?>
      <div class="container">
        <div class="row justify-content-center">
            <?php foreach ($cursor as $pricing): ?>
                <div class="col-lg-4 col-md-8 pricing-container">
                    <div class="ps-item">
                        <h3><?php echo $pricing['name']; ?></h3>
                            <div class="pi-price">
                             <h2><?php echo 'Rs ' . $pricing['price']; ?></h2>
                             <span><?php echo $pricing['description']; ?></span>
                            </div>
                            <ul class="features">
                            <?php foreach ($pricing['features'] as $feature): ?>
                                <li><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <a href="/membership-form.php" class="primary-btn pricing-btn">Enroll now</a>
                    <a href="#" class="thumb-icon"></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </section>
    <!-- Pricing Section End -->
     <!-- ChoseUs Section Begin -->
   <section class="choseus-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Why chose us?</span>
                        <h2>ZENFIT WILL PROVIDE YOU</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-034-stationary-bike"></span>
                        <h4>Modern equipment</h4>
                        <p>Our gym is equipped with the latest modern fitness gear for your workout needs!</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-033-juice"></span>
                        <h4>Healthy nutrition plan</h4>
                        <p>A healthy nutrition plan focuses on balance: plenty of fruits, veggies, whole grains, lean proteins, and healthy fats.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-002-dumbell"></span>
                        <h4>Professional training plan</h4>
                        <p>A professional training plan is customized, balanced, and includes cardio, strength, flexibility, and rest. Consistency and proper form are crucial</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-014-heart-beat"></span>
                        <h4>Unique to your needs</h4>
                        <p>A unique training plan tailored to your specific needs. Balancing cardio, strength, flexibility, and rest for optimal results.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ChoseUs Section End -->
    <!-- Classes Section Begin -->
    <section class="classes-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Our Classes</span>
                        <h2>WHAT WE CAN OFFER</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="/assets/service1.avif" alt="">
                        </div>
                        <div class="ci-text">
                            <span>STRENGTH</span>
                            <h5>Weightlifting</h5>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="/assets/service2.jpg" height="288.11px" alt="">
                        </div>
                        <div class="ci-text">
                            <span>Cardio</span>
                            <h5>Indoor cycling</h5>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="/assets/service3.jpg" height="288.11px"alt="">
                        </div>
                        <div class="ci-text">
                            <span>STRENGTH</span>
                            <h5>Kettlebell power</h5>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="/assets/muscleg.jpg" alt="">
                        </div>
                        <div class="ci-text">
                            <span>Cardio</span>
                            <h4>Muscle Gaining</h4>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="/assets/service5.jpg" height="335px"alt="">
                        </div>
                        <div class="ci-text">
                            <span>Training</span>
                            <h4>Boxing</h4>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ChoseUs Section End -->
<div>
  

    

   

  

    <!-- Gallery Section Begin -->
    <!-- <div class="gallery-section">
        <div class="gallery">
            <div class="grid-sizer"></div>
            <div class="gs-item grid-wide set-bg" data-setbg="/assets/work1.jpg">
                <a href="/assets/work1.jpg" class="thumb-icon image-popup"><i class="fa fa-picture-o"></i></a>
            </div>
            <div class="gs-item set-bg" data-setbg="img/gallery/gallery-2.jpg">
                <a href="img/gallery/gallery-2.jpg" class="thumb-icon image-popup"><i class="fa fa-picture-o"></i></a>
            </div>
            <div class="gs-item set-bg" data-setbg="img/gallery/gallery-3.jpg">
                <a href="./img/gallery/gallery-3.jpg" class="thumb-icon image-popup"><i class="fa fa-picture-o"></i></a>
            </div>
            <div class="gs-item set-bg" data-setbg="img/gallery/gallery-4.jpg">
                <a href="./img/gallery/gallery-4.jpg" class="thumb-icon image-popup"><i class="fa fa-picture-o"></i></a>
            </div>
            <div class="gs-item set-bg" data-setbg="img/gallery/gallery-5.jpg">
                <a href="./img/gallery/gallery-5.jpg" class="thumb-icon image-popup"><i class="fa fa-picture-o"></i></a>
            </div>
            <div class="gs-item grid-wide set-bg" data-setbg="img/gallery/gallery-6.jpg">
                <a href="./img/gallery/gallery-6.jpg" class="thumb-icon image-popup"><i class="fa fa-picture-o"></i></a>
            </div>
        </div>
    </div> -->
    <!-- Gallery Section End -->

  

    <!-- Get In Touch Section Begin -->
 
    <!-- Get In Touch Section End -->

</div>

<!-- <div>

</div>

<div class="products">
<center>
  <h1 class="cardh2" style="color: lightblue;
  margin-top: 0px;font-weight: bolder; " > 
  Products
</h1>
</center> <a href="home.php">
  <img src="/assets/slider3.webp" alt="" srcset="" width="1550px"> </a>
</div>
<div>

</div> -->
<div class="gettouch-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="gt-text">
                        <i class="fa fa-map-marker"></i>
                        <p>Halar Road Valsad<br/> </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gt-text">
                        <i class="fa fa-mobile"></i>
                        <ul>
                            <li>6355760186</li>
                            <li>9510020105</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gt-text email">
                        <i class="fa fa-envelope"></i>
                        <p>zenfit.india@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Get In Touch Section End -->

   
    <?php
include('component\footer.html');

?>