<?php
include('config.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
};

if(isset($_POST['add_product'])){
    
    $name = $_POST['name'];
    $name = filter_var($name,FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price,FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category,FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details,FILTER_SANITIZE_STRING);
    
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_img/'.$image;
    
    $select_product = $conn->prepare("SELECT * FROM products WHERE name=?");
    $select_product->execute([$name]);
    
    if($select_product->rowCount()>0){
        $message[] = 'product name already exist';
    }else{
        $insert_product = $conn->prepare("INSERT INTO products(name,price,category,details,image) VALUES(?,?,?,?,?)");
        $insert_product->execute([$name,$price,$category,$details,$image]);
    
        if($insert_product){
            if($image_size>20000000){
                $message[] = 'image size too large';
            }else{
                move_uploaded_file($image_tmp_name,$image_folder);
                $message[] = 'new product added!';
            }
        }
    }


    
};


if(isset($_GET['delete'])){

    $delete_id = $_GET['delete'];
    $select_delete_image = $conn->prepare("SELECT image FROM products WHERE id=?");
    $select_delete_image->execute([$delete_id]);
    $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
    unlink('uploaded_img/'.$fetch_delete_image['image']);
    $delete_products = $conn->prepare("DELETE  FROM products WHERE id =?");
    $delete_products->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE  FROM wishlist WHERE pid =?");
    $delete_wishlist->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE  FROM cart WHERE pid =?");
    $delete_cart->execute([$delete_id]);
   
    header('location:admin_products.php');
    $message[] = 'product deleted successfully';
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin products</title>

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
<!-- admin header starts -->
<?php include('admin_header.php'); ?>
<!-- admin header ends -->

<!-- adding products section starts here -->
<section class="add-product">
    
<h1 class="title">add products</h1>

<form action="" method="post" enctype="multipart/form-data">
    <div class="flex">
        <div class="input-box">
            <input type="text" required placeholder="enter product name" name="name" class="box">
            <select name="category" required id="category" class="box">
                <option value="" selected disabled>select category</option>
                <option value="fruits">fruits</option>
                <option value="fish">fish</option>
                <option value="meat">meat</option>
                <option value="vegetables">vegetables</option>
            </select>
        </div>

        <div class="input-box">
            <input type="number" required min="0" name="price" placeholder="enter product price" class="box">
            <input type="file" required name="image" class="box" accept="image/png,image/jpg,image/jpeg">
        </div>
    </div>
    <textarea name="details" required  class="box" cols="30" rows="10" placeholder="enter product details"></textarea>
    <input type="submit" name="add_product" class="btn" value="add product">
</form>
</section>

<!-- adding products section ends here -->

<!-- show products section starts here -->
<section class="show-products">
    
    <h1 class="title">products added</h1>

    <div class="box-container">
        <?php 
            $show_products = $conn->prepare("SELECT * FROM products");
            $show_products->execute();
            if($show_products->rowCount()){
                while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){
            
        ?>

        <div class="box">
            <div class="price">$<?= $fetch_products['price'];?>/-</div>
            <img src="uploaded_img/<?= $fetch_products['image'];?>" alt="">
            <div class="name"><?= $fetch_products['name'];?></div>
            <div class="category"><?= $fetch_products['category'];?></div>
            <div class="details"><?= $fetch_products['details'];?></div>
            <div class="flex-btn">
                <a href="admin_update_product.php?update=<?= $fetch_products['id'];?>" class="option-btn">update</a>
                <a href="admin_products.php?delete=<?= $fetch_products['id'];?>" class="delete-btn">delete </a>
            </div>
        </div>

        <?php
          }
    }else{
        echo'<p class="empty">no product added yet!</p>';
     }
        ?>
    </div>
</section>
<!-- show products section ends here -->


</body>
</html>