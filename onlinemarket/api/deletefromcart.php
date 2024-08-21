<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "You need to login before"]);
    exit;
}

include("connect.php");

if (isset($_POST["product_id"]) && !empty($_POST["product_id"])) {
    $pid = $_POST["product_id"];
    $uid = $_SESSION["user_id"];

    $query = "SELECT quantity FROM cart WHERE user_id=? AND product_id=?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $uid, $pid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $productInCart = mysqli_num_rows($result) > 0;

    if ($productInCart) {
        
        $query = "DELETE FROM cart WHERE user_id=? AND product_id=?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $uid, $pid);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete product"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "We can't find this product id in your cart"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid product id"]);
}
?>
