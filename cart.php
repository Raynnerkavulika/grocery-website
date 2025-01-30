



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart page</title>

        <!-- font awesome cdn link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
     <!--custom css file link -->
      <link rel="stylesheet" href="style.css">
      
</head>
<body>
    <!-- admin header begins -->
    <?php include 'header.php';?>
    <!-- admin header ends -->

    <section class="cart">
        <h1 class="title">products added</h1>

        <div class="box-container">
            <?php 
              $grand_total = 0;
              $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
              $select_cart->execute([$user_id]);
              if($select_cart->rowCount()>0){
                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
            ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <a href="cart.php?delete=<?= $fetch_cart['id'];?>" onclick="return confirm('are you sure you want to delete this item from the wishlist?');" class="fas fa-times"></a>
                    <a href="view_page.php?pid=<?= $fetch_cart['pid'];?>" class="fas fa-eye"></a>
                    <img src="uploaded_img/<?= $fetch_cart['image'];?>" alt="">
                    <div class="price">sh <?= $fetch_cart['price'];?>/-</div>
                    <div class="name"><?= $fetch_cart['name'];?></div>
                    <input type="hidden" name="cart_id" value="<?= $fetch_cart['id'];?>">
                    <div class="flex-btn">
                      <input type="number" name="p_qty" min="1" value="<?= $fetch_cart['quantity'];?>" class="qty">
                       <input type="submit" value="update" name="update_quantity" class="option-btn">
                    </div>
                    <div class="sub-total">sub total: <span>Sh <?= $sub_total =  $fetch_cart['price'] * $fetch_cart['quantity'] ?>/-</span></div>
                </form>
            <?php  
                  $grand_total += $sub_total;
                  }
                }else{
                  echo '<p class="empty">Your cart is empty</p>';
                }
            ?>
        </div>
    </section>
