<?php
include('config.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
};


    if(isset($_GET['delete'])){

        $delete_id = $_GET['delete'];
        $delete_message = $conn->prepare("DELETE FROM message WHERE id=?");
        $delete_message->execute([$delete_id]);
        $message[] ="message deleted successfully";
        
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


<section class="messages">
    <h1 class="title">messages</h1>

    <div class="box-container">
        <?php
         $select_messages = $conn->prepare("SELECT * FROM message");
         $select_messages->execute();
         if($select_messages->rowCount()){
        while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
        ?>

        <div class="box">
            <p>user id: <span><?= $fetch_message['id'];?></span></p>
            <p>name: <span><?= $fetch_message['name'];?></span></p>
            <p>email: <span><?= $fetch_message['email'];?></span></p>
            <p>number: <span> <?= $fetch_message['number'];?></span></p>
            <p>message: <span> <?= $fetch_message['message'];?></span></p>
            <a href="admin_contacts.php?delete=<?= $fetch_message['id'];?>" class="delete-btn" onclick="return confirm('delete this message?');">delete</a>
       </div>
            
        <?php
            }
            }else{
            echo'<p class="empty">you have no messages yet!</p>
            ';
            }

        ?>
    </div>
</section>
</body>
</html>