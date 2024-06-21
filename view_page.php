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
    <!-- css file link -->
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

  <section class="quick-view">
    <h1 class="title">quick view</h1>
  <?php 
        $pid = $_GET['pid'];
        $select_products = $conn->prepare("SELECT * FROM products WHERE id=?");
        $select_products->execute([$pid]);
        if($select_products->rowCount()>0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>

        <form action="" method="post" class="box" enctype="multipart/form-data">
            <div class="price">$<?= $fetch_products['price'];?>/-</div>
           <img src="uploaded_img/<?= $fetch_products['image'];?>" alt="">
           <div class="name"><?= $fetch_products['name'];?></div>
           <div class="details"><?= $fetch_products['details'];?></div>
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
  </section>
</body>
</html>