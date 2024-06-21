<?php
include('config.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
};


    if(isset($_GET['delete'])){
        
        $delete_id = $_GET['delete'];
        $delete_user = $conn->prepare("DELETE FROM users WHERE id=?");
        $delete_user->execute([$delete_id]);
        $message[] ="user deleted successfully";
    }
    



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>
     <!-- font awesome cdn link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- custom css link -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- admin header starts -->
<?php include('admin_header.php'); ?>
<!-- admin header ends -->


<section class="user-accounts">

 <h1 class="title">user accounts</h1>

    <div class="box-container">
        <?php  
        $select_users = $conn->prepare("SELECT * FROM users");
        $select_users->execute();
        if($select_users->rowCount()>0){
            while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
        ?>
       <div class="box" style="<?php if($fetch_users['id'] == $admin_id){ echo'display:none;';} ?>">
        <img src="uploaded_img/<?= $fetch_users['image'];?>" alt="">
        <p>user id: <span><?= $fetch_users['id'];?></span></p>
        <p>username: <span><?= $fetch_users['name'];?></span></p>
        <p>email: <span><?= $fetch_users['email'];?></span></p>
        <p>user type: <span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo'orange;';} ?>"> <?= $fetch_users['user_type'];?></span></p>
        <a href="admin_users.php?delete=<?= $fetch_users['id'];?>" class="delete-btn" onclick="return confirm('delete this user');">delete</a>
       </div>
       <?php
          }
    }else{
        echo'<p class="empty">no user registered here yet!</p>';
     }
        ?>
    </div>
</section>
</body>
</html>