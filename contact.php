<?php 
include('config.php');

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
     header('location:login.php');
};

if(isset($_POST['send_message'])){
    $name = $_POST['name'];
    $name = filter_var($name,FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email,FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number,FILTER_SANITIZE_STRING);
    $message = $_POST['message'];
    $message = filter_var($message,FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM message WHERE name=? AND email=? AND number=? AND message=?");
   $select_message->execute([$name,$email,$number,$message]);
   
   if($select_message->rowCount()>0){
    $message[] = 'Message already sent';
   }else{
    $insert_message = $conn->prepare("INSERT INTO message(user_id,name,email,number,message) VALUES(?,?,?,?,?)");
    $insert_message->execute([$user_id,$name,$email,$number,$message]);
    $message[] = 'message sent successfully';
   }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact page</title>
     <!-- font awesome cdn link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- custom css file link -->
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
    <!-- header starts here -->
<?php  include('header.php');?>
    <!-- header ends here -->

    <!-- contact page starts -->

    <section class="contact">
        <h1 class="title">messages</h1>


       <form action="" method="post">
        <input type="text" name="name" placeholder="enter your name" class="box">
        <input type="email" name="email" placeholder="enter your email" class="box">
        <input type="number" name="number" min="0" placeholder="enter your number" class="box">
        <textarea name="message" placeholder="enter your message" cols="30" rows="10" class="box"></textarea>
        <input type="submit" value="send message" name="send_message" class="btn">
       </form>

    </section>
    <!-- contact page ends -->
</body>
</html>