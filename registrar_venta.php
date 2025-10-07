<?php
session_start();
include 'db.php';

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Obtener precio y stock actual
$res = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $res->fetch_assoc();

if ($product['stock'] >= $quantity) {
    $total = $product['price'] * $quantity;
    // Insertar venta
    $conn->query("INSERT INTO sales (product_id, quantity, total) VALUES ($product_id, $quantity, $total)");
    // Actualizar stock
    $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
    header("Location: dashboard.php");
} else {
    echo "Stock insuficiente.";
}
?>
