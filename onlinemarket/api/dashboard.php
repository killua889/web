<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.0 403 Forbidden');
    echo "You don't have permission to see that";
    exit();
}
$message="";
include("connect.php");

// Handle product deletion
if (isset($_POST['delete']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);

    if (mysqli_stmt_execute($stmt)) {
        $message="Product deleted successfully.";
    } else {
        $message="Failed to delete product.";
    }
}

// Handle product save (add/update)
if (isset($_POST['save_product'])) {
    $id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $description = mysqli_real_escape_string($connect, $_POST['description']);
    $price = floatval($_POST['price']);
    $num = intval($_POST['num']);

    // Handle image upload
    $img_name = '';
    if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['img']['tmp_name'];
        $img_name = basename($_FILES['img']['name']);
        
    } 
    if (isset($_POST['esisting_img'])&&!empty($_POST['esisting_img'])) {
        $img_name = $_POST['esisting_img'];
    }

    if ($id) {
        // Update product
        $query = "UPDATE products SET name=?, description=?, price=?, image=?, num=? WHERE id=?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'sssssi', $name, $description, $price, $img_name, $num, $id);
    } else {
        // Insert new product
        $query = "INSERT INTO products (name, description, price, image, num) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'ssssi', $name, $description, $price, $img_name, $num);
    }

    if (mysqli_stmt_execute($stmt)) {


    $message= $id ? "Product updated successfully." : "Product added successfully.";

    } else {
        $message ="Failed to save product.". "<br>Query: " . $query."<br>Error: " . mysqli_error($connect);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    Fox Online Shop
</header>
<form action="logout.php" method="post">
    <input type="hidden" name="logout" value="yes">
    <p>Do you want to logout !</p>
    <input type="submit" value="logout">
</form>
<div class="container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Img</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM products";
            $result = mysqli_query($connect, $query);
            if (!$result) {
                die("Query failed: " . mysqli_error($connect));
            }
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td>
                        <img src="imgs/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100px;">
                    </td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['num']); ?></td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="delete"class="delete">Delete</button>
                        </form>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                            <input type="hidden" name="description" value="<?php echo htmlspecialchars($row['description']); ?>">
                            <input type="hidden" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">
                            <input type="hidden" name="num" value="<?php echo htmlspecialchars($row['num']); ?>">
                            <input type="hidden" name="existing_img" value="<?php echo htmlspecialchars($row['image']); ?>">
                            <button type="submit" name="edit_product">Edit</button>
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <h2>ADD || Edit</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo isset($_POST['edit_product']) ? htmlspecialchars($_POST['product_id']) : ''; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo isset($_POST['edit_product']) ? htmlspecialchars($_POST['name']) : ''; ?>" required><br>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?php echo isset($_POST['edit_product']) ? htmlspecialchars($_POST['description']) : ''; ?>" required><br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo isset($_POST['edit_product']) ? htmlspecialchars($_POST['price']) : ''; ?>" required><br>
        <label for="num">Quantity:</label>
        <input type="number" id="num" name="num" value="<?php echo isset($_POST['edit_product']) ? htmlspecialchars($_POST['num']) : ''; ?>" required><br>
        <label for="img">Image:</label>
        <input type="file" id="img" name="img"><br>
        <input type="hidden" name="esisting_img" value="<?php echo isset($_POST['edit_product']) ? htmlspecialchars($_POST['existing_img']) : ''; ?>">
        <button type="submit" name="save_product">Save</button>
    </form>
</div>
<?php if ($message): ?>
<script>
    alert("<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>");
</script>
<?php endif; ?>
</body>
</html>
