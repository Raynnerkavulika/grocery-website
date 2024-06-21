<?php
include('config.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
};

if(isset($_POST['update_order'])){
    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $update_order = $conn->prepare("UPDATE orders SET payment_status=? WHERE id=?");
    $update_order->execute([$update_payment,$order_id]);
    $message[] = "payment has been updated";

};

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM orders WHERE id=?");
    $delete_order->execute([$delete_id]);
    $message[] ="order deleted successfully";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin orders</title> 
    
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- custom css link -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- admin header starts -->
<?php include('admin_header.php'); ?>
<!-- admin header ends -->

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

<section class="placed-orders">
    <h1 class="title">placed orders</h1>
    <div class="box-container">
        <?php
          $select_orders = $conn->prepare("SELECT * FROM orders");
          $select_orders->execute();

          if($select_orders->rowCount()>0){
        while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
        
        ?>

        <div class="box">
            <p>userid: <span><?= $fetch_orders['user_id'];?></span></p>
            <p>placed on: <span><?= $fetch_orders['placed_on'];?></span></p>
            <p>name: <span><?= $fetch_orders['name'];?></span></p>
            <p>email: <span><?= $fetch_orders['email'];?></span></p>
            <p>number: <span><?= $fetch_orders['number'];?></span></p>
            <p>address: <span><?= $fetch_orders['address'];?></span></p>
            <p>total products: <span><?= $fetch_orders['total_products'];?></span></p>
            <p>total price: <span>$<?= $fetch_orders['total_price'];?>/=</span></p>
            <p>payment method: <span><?= $fetch_orders['method'];?></span></p>

            <form action="" method="post">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id'];?>">
            <select name="update_payment" required class="dropdown">
                <option value="" selected disabled><?= $fetch_orders['payment_status'];?></option>
                <option value="pending">pending</option>
                <option value="completed">completed</option>
            </select>

            <div class="flex-btn">
                <input type="submit" name="update_order" value="update" class="option-btn">
                <a href="admin_orders.php?delete=<?= $fetch_orders['id'];?>" class="delete-btn" >delete</a>
            </div>
            </form>
        </div>

        <?php
        
    }
}else{
  echo'    <p class="empty">no orders placed yet!</p>
  ';
}
        ?>
    </div>
</section>
</body>
</html>