<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']);

// 1️⃣ Get image path from DB
$sql = "SELECT image_path FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$image_path = $product['image_path']; // e.g. uploads/img.jpg

// 2️⃣ Delete product from DB
$delete_sql = "DELETE FROM products WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $product_id);

if ($delete_stmt->execute()) {

    // 3️⃣ Delete image from folder
    if (!empty($image_path)) {

        // Correct full server path
        $full_path = __DIR__ . '/../' . $image_path;

        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }

    header("Location: adminproduct.php?deleted=1");
    exit();

} else {
    echo "Failed to delete product.";
}
?>
