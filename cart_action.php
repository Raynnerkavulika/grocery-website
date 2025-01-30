<?php  

include 'config.php';
session_start();

header('Content-Type: application/json');

$response = ["success" => false , "message" => "invalid request"];

if(!isset($_SESSION['user_id'])){
    echo json_encode(["success" => false , "message" => "user not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
$action = $_POST['action'] ?? '';

if($action === "delete"){
    $cart_id = $_POST['cart_id'] ?? 0;
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
    $delete_cart_item->execute([$cart_id,$user_id]);
    if($delete_cart_item ->rowCount()>0){
        $response = ["success" =>true , "message" => "item has been deleted successfully"];
      }else{
        $response = ["success" =>false , "message" => "item not found or has already been deleted"];
      }
}elseif($action === "delete_all"){
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    if($delete_cart_item ->rowCount()>0){
        $response = ["success" =>true , "message" => "all items have been deleted successfully"];
      }else{
        $response = ["success" =>false , "message" => "Your cart is empty"];
      }
}elseif($action === "update_quantity"){
    $cart_id = $_POST['cart_id'] ?? 0;
    $quantity = filter_var($_POST['quantity'] ?? 1 , FILTER_SANITIZE_NUMBER_INT);

    if($quantity <= 0){
        echo json_encode(["success" => false , "message" => "invalid quantity"]);
        exit;
    }

    $update_quantity = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ? AND user_id = ?");
    $update_quantity->execute([$quantity,$cart_id,$user_id]);
    if($update_quantity -> rowCount() > 0){
        $response = ["success" =>true , "message" => "cart quantity has been updated successfully"];
    }else{
        $response = ["success" =>false , "message" => "no changes made or item found"];
    }
}
}

echo json_encode($response);
?>