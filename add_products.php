<?php
session_start();
require 'config.php';

// Security Check
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price']]);
}

header('Location: admin_products.php');
exit();
