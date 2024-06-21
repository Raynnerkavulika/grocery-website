<?php
include('config.php');

session_start();
 
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
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

    <!-- custom css  file link -->
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
<!-- admin header starts -->
<?php include('admin_header.php'); ?>
<!-- admin header ends -->

<!-- dashboard starts here -->
<section class="dashboard">

<h1 class="title">dashboard</h1>
    <div class="box-container">

            <div class="box">
                <?php
                 $total_pendings = 0;
                 $select_pending = $conn->prepare("SELECT * FROM orders WHERE payment_status=?");
                 $select_pending->execute(['pending']);
                 while($fetch_pending = $select_pending->fetch(PDO::FETCH_ASSOC)){
                    $total_pendings += $fetch_pending['total_price'];
                 };
                
                ?>
                <h3>$<?= $total_pendings;?>/-</h3>
                <p>pending orders</p>
                <a href="admin_orders.php" class="btn">see orders</a>
            </div>

            <div class="box">
                <?php
                 $total_completed = 0;
                 $select_completed = $conn->prepare("SELECT * FROM orders WHERE payment_status=?");
                 $select_completed->execute(['completed']);
                 while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
                    $total_completed += $fetch_completed['total_price'];
                 };
                
                ?>
                <h3>$<?= $total_completed;?>/-</h3>
                <p>completed orders</p>
                <a href="admin_orders.php" class="btn">see orders</a>
            </div>

         
            <div class="box">
                <?php
                 $select_orders = $conn->prepare("SELECT * FROM orders");
                 $select_orders->execute();
                 $number_of_orders = $select_orders->rowCount();
                ?>
                <h3><?= $number_of_orders;?></h3>
                <p>orders placed</p>
                <a href="admin_orders.php" class="btn">see orders</a>
            </div>

            <div class="box">
                <?php
                 $select_products = $conn->prepare("SELECT * FROM products");
                 $select_products->execute();
                 $number_of_products = $select_products->rowCount();
                ?>
                <h3><?= $number_of_products;?></h3>
                <p>products added</p>
                <a href="admin_products.php" class="btn">see products</a>
            </div>

            <div class="box">
                <?php
                 $select_users = $conn->prepare("SELECT * FROM users WHERE user_type =?");
                 $select_users->execute(['user']);
                 $number_of_users = $select_users->rowCount();
                ?>
                <h3><?= $number_of_users;?></h3>
                <p>total users</p>
                <a href="admin_users.php" class="btn">see accounts</a>
            </div>

            <div class="box">
                <?php
                 $select_admin = $conn->prepare("SELECT * FROM users WHERE user_type =?");
                 $select_admin->execute(['admin']);
                 $number_of_admin = $select_admin->rowCount();
                ?>
                <h3><?= $number_of_admin;?></h3>
                <p>total admins</p>
                <a href="admin_users.php" class="btn">see accounts</a>
            </div>

            <div class="box">
                <?php
                 $select_accounts = $conn->prepare("SELECT * FROM users ");
                 $select_accounts->execute();
                 $number_of_accounts = $select_accounts->rowCount();
                ?>
                <h3><?= $number_of_accounts;?></h3>
                <p>total accounts</p>
                <a href="admin_users.php" class="btn">see accounts</a>
            </div>

            <div class="box">
                <?php
                 $select_messages = $conn->prepare("SELECT * FROM message");
                 $select_messages->execute();
                 $number_of_messages = $select_messages->rowCount();
                ?>
                <h3><?= $number_of_admin;?></h3>
                <p>total messages</p>
                <a href="admin_contacts.php" class="btn">see messages</a>
            </div>
    </div>
</section>

<!-- dashboard ends -->

</body>
</html>