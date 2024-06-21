<header class="header">
<div class="flex">

<a href="home.php" class="logo">Groco<span>.</span></a>

     <div class="navbar">
        <a href="home.php">home</a>
        <a href="shop.php">shop</a>
        <a href="orders.php">orders</a>
        <a href="about.php">about</a>
        <a href="contact.php">contact</a>
     </div>

     <div class="icons">
        <div id="user-btn" class="fas fa-user"></div>
        <div id="menu-btn" class="fas fa-bars"></div>
        <a href="search.php" class="fas fa-search"></a>
        <?php
        $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
        $count_cart_items->execute([$user_id]);
        $count_wishlist_items = $conn->prepare("SELECT * FROM wishlist WHERE user_id=?");
        $count_wishlist_items->execute([$user_id]);
        
        ?>
        <a href="wishlist.php"> <i class="fas fa-heart"></i><span>(<?=  $count_wishlist_items->rowCount();?>)</span></a>
        <a href="cart.php"> <i class="fas fa-shopping-cart"></i><span>(<?=  $count_cart_items->rowCount();?>)</span></a>
     </div>

     <div class="profile">
        <?php
           $select_profile = $conn->prepare("SELECT * FROM users WHERE id=?");
           $select_profile->execute([$user_id]);
           $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        
        ?>
        <img src="uploaded_img/<?= $fetch_profile['image'];?>" alt="">
        <h3><?= $fetch_profile['name'];?></h3>

        <a href="user_update_profile.php" class="btn">update profile</a>
        <a href="logout.php" class="delete-btn">logout</a>
     </div>
</div>
    
</header>


