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
$action = $_POST['action'] ?? 0;

if($action === "delete"){
    $cart_id = $_POST['cart_id'] ?? 0;
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
    $delete_cart_item->execute([$cart_id,$user_id]);
        $response = ["success" =>true , "message" => "item has been deleted successfully"];
}elseif($action === "delete_all"){
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
        $response = ["success" =>true , "message" => "all items have been deleted successfully"];
}elseif($action === "update_quantity"){
    $cart_id = $_POST['cart_id'];
    $quantity = filter_var($_POST['quantity'] ?? 1 , FILTER_SANITIZE_NUMBER_INT);
    $update_quantity = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ? AND user_id = ?");
    $update_quantity->execute([$quantity,$cart_id,$user_id]);
        $response = ["success" =>true , "message" => "cart quantity has been updated successfully"];
}
}

echo json_encode($response);
?>