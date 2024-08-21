<?php
session_start();
include("connect.php");

header('Content-Type: application/json');

if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
            echo json_encode(['success' => true, 'message' => 'Login successful', 'role' => 'admin']);
        } elseif ($row['role'] == 'user') {
            echo json_encode(['success' => true, 'message' => 'Login successful', 'role' => 'user']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
}
?>
