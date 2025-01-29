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
}
?>