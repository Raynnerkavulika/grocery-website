<header class="header">
<div class="flex">

<a href="admin_page.php" class="logo">Admin<span>panel</span></a>

     <div class="navbar">
        <a href="admin_page.php">home</a>
        <a href="admin_products.php">products</a>
        <a href="admin_orders.php">orders</a>
        <a href="admin_users.php">users</a>
        <a href="admin_contacts.php">messages</a>
     </div>

     <div class="icons">
        <div id="user-btn" class="fas fa-user"></div>
        <div id="menu-btn" class="fas fa-bars"></div>
     </div>

     <div class="profile">
        <?php
           $select_profile = $conn->prepare("SELECT * FROM users WHERE id=?");
           $select_profile->execute([$admin_id]);
           $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        
        ?>
        <img src="uploaded_img/<?= $fetch_profile['image'];?>" alt="">
        <h3><?= $fetch_profile['name'];?></h3>

        <a href="admin_update_profile.php" class="btn">update profile</a>
        <a href="logout.php" class="delete-btn">logout</a>
     </div>
</div>
    
</header>

