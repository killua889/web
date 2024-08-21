<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "You need to login before"]);
} else {
    include("connect.php");

    if (isset($_POST["product_id"]) && !empty($_POST["product_id"])) {
        $pid = $_POST["product_id"];
        $uid = $_SESSION["user_id"];
        $quantity = 1;

        if (isset($_POST["quantity"]) && $_POST["quantity"] >= 0) {
            $quantity = $_POST["quantity"];
        }

        // Check stock availability
        $query = "SELECT num FROM products WHERE id=?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'i', $pid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            $max = $row['num'];
            if ($quantity > $max) {
                echo json_encode(['success' => false, 'message' => "The max number in stock is ${max}"]);
            } else {
                // Check if the product is already in the cart
                $query = "SELECT quantity FROM cart WHERE user_id=? AND product_id=?";
                $stmt = mysqli_prepare($connect, $query);
                mysqli_stmt_bind_param($stmt, 'ii', $uid, $pid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $productInCart = mysqli_num_rows($result) > 0;

                if ($productInCart) {
                    // Update the quantity if the product is already in the cart
                    $query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                    $stmt = mysqli_prepare($connect, $query);
                    mysqli_stmt_bind_param($stmt, 'iii', $quantity, $uid, $pid);
                } else {
                    // Insert into cart if the product is not already there
                    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($connect, $query);
                    mysqli_stmt_bind_param($stmt, 'iii', $uid, $pid, $quantity);
                }

                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(["success" => true, "message" => "Product updated successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to update product"]);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => "Product not found"]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => "Product ID is required"]);
    }
}
?>
