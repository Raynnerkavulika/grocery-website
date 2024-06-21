<?php 
include('config.php');

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
     header('location:login.php');
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
     <!-- font awesome cdn link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php  include('header.php');?>

<section class="about">
    <h1 class="title">about us</h1>

    <div class="row">
        <div class="box">
            <img src="images/about-img-1.png" alt="">
            <h3>why choose us?</h3>
            <p>Lorem ipsum dolor sit amet consectetur 
                adipisicing elit. Unde excepturi assumenda ipsum fuga 
                incidunt, doloremque veritatis eaque ullam dolorum esse amet quia ratione, 
                nostrum nisi minima? Velit sint id impedit?</p>
                <a href="contact.php" class="btn">contact us</a>
        </div>

        <div class="box">
            <img src="images/about-img-2.png" alt="">
            <h3>what we provide</h3>
            <p>Lorem ipsum dolor sit amet consectetur 
                adipisicing elit. Unde excepturi assumenda ipsum fuga 
                incidunt, doloremque veritatis eaque ullam dolorum esse amet quia ratione, 
                nostrum nisi minima? Velit sint id impedit?</p>
                <a href="shop.php" class="btn">our shop</a>
        </div>
    </div>
</section>

<section class="reviews">
    
</section>
</body>
</html>