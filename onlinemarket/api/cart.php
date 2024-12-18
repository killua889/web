<?php
session_start();
if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false,"message"=>"You need to login before"]);
}
elseif(empty($_SESSION['user_id'])){
    echo json_encode(['success'=>false,"message"=>"User ID is required"]);
}
else{
    include("connect.php");
    $user_id=$_SESSION['user_id'];
    $query = "
    SELECT 
        p.id, 
        p.name, 
        p.description, 
        p.price, 
        p.image, 
        p.num, 
        c.quantity 
    FROM 
        carts c 
    JOIN 
        products p 
    ON 
        c.product_id = p.id 
    WHERE 
        c.user_id = ?
";
$stmt = mysqli_prepare($connect, $query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    
}else{
mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
$products = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    echo json_encode(['success' => true, 'products' => $products]);
}
?>