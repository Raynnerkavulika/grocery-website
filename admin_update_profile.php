<?php
include('config.php');
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
     header('location:login.php');
}

if(isset($_POST['update_profile'])){

    $name = $_POST['name'];
    $name = filter_var($name,FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email,FILTER_SANITIZE_STRING);

    $update_profile = $conn->prepare("UPDATE users SET name=?,email=? WHERE id=?");
    $update_profile->execute([$name,$email,$admin_id]);

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_img/'.$image;
    $old_image = $_POST['old_image'];

    if(!empty($image)){
        if($image_size>20000000){
            $message[] = "image size too large";
        }else{
            $update_image = $conn->prepare("UPDATE users SET image=? WHERE id=?");
            $update_image->execute([$image,$admin_id]);
            if($update_image){
                move_uploaded_file($image_tmp_name,$image_folder);
                unlink('uploaded_img/'.$old_image);
                $message[] = "Image updated successfully";
            };
    };
    };

    $old_pass = $_POST['old_pass'];
    $update_pass = $_POST['update_pass'];
    $update_pass = filter_var($update_pass,FILTER_SANITIZE_STRING);
    $new_pass = $_POST['new_pass'];
    $new_pass = filter_var($new_pass,FILTER_SANITIZE_STRING);
    $confirm_pass = $_POST['confirm_pass'];
    $confirm_pass = filter_var($confirm_pass,FILTER_SANITIZE_STRING);

    if(!empty($update_pass) OR !empty($new_pass) OR !empty($confirm_pass)){
    if($update_pass != $old_pass){
        $message[] = 'old password not matched';
    }elseif($new_pass != $confirm_pass){
        $message[] = 'confirm password not matched';
    }else{
        $update_password = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update_password->execute([$confirm_pass,$admin_id]);
        $message[] = 'password updated successfully';
    }
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin update profile</title>
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


<section class="update-profile">
  
        <h1 class="title">update profile</h1>

        <form action="" method="post" enctype="multipart/form-data">
        <img src="uploaded_img/<?= $fetch_profile['image'];?>" alt="">
            <div class="flex">

            <div class="inputBox">
                    <span>username:</span>
                <input type="text" placeholder="enter your name" value="<?=$fetch_profile['name'];?>" name="name" class="box">
                    <span>email:</span>
                <input type="email" placeholder="enter your email" value="<?=$fetch_profile['email'];?>" name="email" class="box">
                    <span>profile pic:</span>
                <input type="file"  name="image" class="box" accept="image/png,image/jpg,image/jpeg">
                <input type="hidden" name="old_image" value="<?=$fetch_profile['image'];?>">
            </div>

            <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password'];?>">
                    <span>old password:</span>
                <input type="password" placeholder="enter old password"  name="update_pass" class="box">
                    <span>new password:</span>
                <input type="password" placeholder="enter new password"  name="new_pass" class="box">
                    <span>confirm password:</span>
                <input type="password" placeholder="confirm new password"  name="confirm_pass" class="box">
            </div>

            </div>
            <div class="flex-btn">
                <input type="submit" name="update_profile" class="btn" value="update profile">
                <a href="admin_page.php" class="option-btn">go back</a>
            </div>
        </form>

            
           
</section>
</body>
</html>