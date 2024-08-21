<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, "message" => "You need to log in before accessing this page."]);
    exit();
}

include("connect.php");

$userid = $_SESSION["user_id"];
$query = "SELECT * FROM users WHERE id=?";
$stmt = mysqli_prepare($connect, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            echo json_encode(["success" => true, "user" => $row]);
        } else {
            echo json_encode(['success' => false, "message" => "User not found."]);
        }
    } else {
        echo json_encode(['success' => false, "message" => "Failed to retrieve user information."]);
    }

    mysqli_stmt_close($stmt); 
} else {
    echo json_encode(['success' => false, "message" => "Database query preparation failed."]);
}

mysqli_close($connect); 
?>
