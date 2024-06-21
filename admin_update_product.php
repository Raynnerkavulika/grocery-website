<?php
include('config.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
};


if(isset($_POST['update_product'])){
    
    $pid = $_POST['pid'];
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
    $old_image = $_POST['old_image'];
  

    $update_product  = $conn->prepare("UPDATE products SET name=?,price=?,category=?,details=? WHERE id=?");
    $update_product->execute([$name,$price,$category,$details,$pid]);
    $message[] = 'product upated successfully';

    if(!empty($image)){
        if($image_size>20000000){
            $message[] = 'image size too large';
        }else{
            $update_image = $conn->prepare("UPDATE products SET image=? WHERE id=?");
            $update_image->execute([$image,$pid]);

            if($update_image){
                move_uploaded_file($image_tmp_name,$image_folder);
                unlink('uploaded_img/'.$old_image);
                $message[] = 'image updated successfully';
            };
        };
    };

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update products</title>
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

<section class="update-products">
    <h1 class="title">update products</h1>

    <?php
      $update_id = $_GET['update'];
      $select_product = $conn->prepare("SELECT * FROM products WHERE id =?");
      $select_product->execute([$update_id]);

      if($select_product->rowCount()){
        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){

    
    ?>
     <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="pid" value="<?= $fetch_product['id'];?>">
        <input type="hidden" name="old_image" value="<?= $fetch_product['image'];?>">
         <img src="uploaded_img/<?= $fetch_product['image'];?>" alt="">
         <input type="text" required name="name" value="<?= $fetch_product['name'];?>" class="box" placeholder="enter product name">
         <input type="number" required name="price" min="0" value="<?= $fetch_product['price'];?>" class="box" placeholder="enter product price">
         <select name="category" required id="category" class="box">
                <option  selected><?= $fetch_product['category'];?></option>
                <option value="fruits">fruits</option>
                <option value="fish">fish</option>
                <option value="meat">meat</option>
                <option value="vegetables">vegetables</option>
        </select>
            <textarea name="details" required class="box" placeholder="enter product details" cols="30" rows="10"><?= $fetch_product['details'];?></textarea>
            <input type="file" name="image" class="box" accept="image/png,image/jpg,image/jpeg">
            <div class="flex-btn">
                <input type="submit" value="update product" class="btn" name="update_product">
                <a href="admin_products.php" class="option-btn">go back</a>
            </div>
     </form>

    <?php
}
        
}else{
   echo'<p class="empty">no products found!</p>';
}
    ?>
</section>
</body>
</html>
