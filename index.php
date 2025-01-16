<?php 
include('config.php');

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
     header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

     $pid = $_POST['pid'];
     $pid = filter_var($pid,FILTER_SANITIZE_STRING);
     $p_name = $_POST['p_name'];
     $p_name = filter_var($p_name,FILTER_SANITIZE_STRING);
     $p_price = $_POST['p_price'];
     $p_price = filter_var($p_price,FILTER_SANITIZE_STRING);
     $p_image = $_POST['p_image'];
     $p_image = filter_var($p_image,FILTER_SANITIZE_STRING);

    $check_wishlist_numbers = $conn->prepare("SELECT * FROM wishlist WHERE name=? AND user_id=?");
    $check_wishlist_numbers->execute([$p_name,$user_id]);

    $check_cart_numbers = $conn->prepare("SELECT * FROM cart WHERE name=? AND user_id=?");
    $check_cart_numbers->execute([$p_name,$user_id]);

    if($check_wishlist_numbers->rowCount()>0){
        $message[] = 'already added to wishlist';
    }elseif($check_cart_numbers->rowCount()>0){
        $message[] = 'already added to cart';
    }else{

        $insert_wishlist = $conn->prepare("INSERT INTO wishlist(user_id,pid,name,price,image) VALUES(?,?,?,?,?)");
        $insert_wishlist->execute([$user_id,$pid,$p_name,$p_price,$p_image]);
        $message[] = 'added to wishlist';
    }
};

if(isset($_POST['add_to_cart'])){

    $pid = $_POST['pid'];
    $pid = filter_var($pid,FILTER_SANITIZE_STRING);
     $p_name = $_POST['p_name'];
     $p_name = filter_var($p_name,FILTER_SANITIZE_STRING);
     $p_price = $_POST['p_price'];
     $p_price = filter_var($p_price,FILTER_SANITIZE_STRING);
     $p_image = $_POST['p_image'];
     $p_image = filter_var($p_image,FILTER_SANITIZE_STRING);
     $p_qty = $_POST['p_qty'];
     $p_qty = filter_var($p_qty,FILTER_SANITIZE_STRING);

    $check_cart_numbers = $conn->prepare("SELECT * FROM cart WHERE name=? AND user_id=?");
    $check_cart_numbers->execute([$p_name,$user_id]);

    if($check_cart_numbers->rowCount()>0){
        $message[] = 'already added to cart';
    }else{

        $check_wishlist_numbers = $conn->prepare("SELECT * FROM wishlist WHERE name=? AND user_id=?");
        $check_wishlist_numbers->execute([$p_name,$user_id]);

        if($check_wishlist_numbers->rowCount()>0){
            $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE name=? AND user_id=?");
            $delete_wishlist->execute([$p_name,$user_id]);
        }

        $insert_cart = $conn->prepare("INSERT INTO  cart(user_id,pid,name,price,quantity,image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id,$pid,$p_name,$p_price,$p_qty,$p_image]);
        $message[] = 'added to cart';
    }
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

    <!-- custom css link -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php  
   if(isset($message)){
    foreach($message as $message){
        echo'
        <div class="message">
            <span>'.$message.'</span>
        </div>
        ';
    }
   }

?>
<?php  include('header.php');?>

<div class="home-bg">
    <section class="home">
        <div class="content">
            <h3>go organice,dont panic</h3>
            <span>Reach for a healthier you with organic foods</span>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum, esse quidem et mollitia iusto delectus. 
                Illum quasi fugiat animi velit tempora iste recusandae, neque optio ratione ab facere natus dicta.</p>
            <a href="about.php" class="btn">about us</a>
        </div>
    </section>
</div>

<section class="home-category">
    <h1 class="title">shop by category</h1>
    <div class="box-container">
        <div class="box">
            <img src="images/cat-1.png" alt="">
            <h3>fruits</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat, debitis perspiciatis inventore, voluptatem optio ad similique explicabo sunt tempora 
                labore quam quis laborum perferendis totam, necessitatibus doloremque repudiandae? Unde, similique.</p>
            <a href="category.php?category=fruits" class="btn">fruits</a>
        </div>

        <div class="box">
            <img src="images/cat-2.png" alt="">
            <h3>meat</h3>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Asperiores illo nulla neque sed, fuga repudiandae. Earum, reprehenderit maiores necessitatibus et,
                 deleniti velit doloribus ea eum sunt dolores vitae nihil doloremque.</p>
            <a href="category.php?category=meat" class="btn">meat</a>
        </div>

        <div class="box">
            <img src="images/cat-3.png" alt="">
            <h3>vegetables</h3>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ab assumenda, temporibus itaque autem similique quam voluptas praesentium reiciendis natus
                 aliquam! Maiores minima debitis error porro unde dolorum animi explicabo veniam.</p>
            <a href="category.php?category=vegetables" class="btn">vegetables</a>
        </div>

        <div class="box">
            <img src="images/cat-4.png" alt="">
            <h3>fish</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing 
                elit. Nostrum aspernatur voluptas illo odit blanditiis 
                obcaecati incidunt, doloremque asperiores mollitia neque 
                quibusdam repudiandae tempora voluptates praesentium in odio
                 fugiat, corporis quas?</p>
            <a href="category.php?category=fish" class="btn">fish</a>
        </div>
    </div>
</section>

<section class="products">
    <h1 class="title">latest products</h1>
    <div class="box-container">
        <?php 
        $select_products = $conn->prepare("SELECT * FROM products LIMIT 6");
        $select_products->execute();
        if($select_products->rowCount()>0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>

        <form action="" method="post" class="box" enctype="multipart/form-data">
            <div class="price">$<?= $fetch_products['price'];?>/-</div>
            <a href="view_page.php?pid=<?= $fetch_products['id'];?>" class="fas fa-eye"></a>
           <img src="uploaded_img/<?= $fetch_products['image'];?>" alt="">
           <div class="name"><?= $fetch_products['name'];?></div>
           <input type="hidden" name="pid" value="<?= $fetch_products['id'];?>">
           <input type="hidden" name="p_price" value="<?= $fetch_products['price'];?>">
           <input type="hidden" name="p_name" value="<?= $fetch_products['name'];?>">
           <input type="hidden" name="p_image" value="<?= $fetch_products['image'];?>">
           <input type="number" name="p_qty" class="qty" min="1" value="1">
           <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn">
           <input type="submit" value="add to cart" name="add_to_cart" class="btn">
        </form>
        <?php
       }
        }else{
         echo'<p class="empty">no products added yet!</p>';
        }
        ?>
    </div>
</section>
</body>
</html>