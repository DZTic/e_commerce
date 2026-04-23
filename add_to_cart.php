<?php
session_start();
require 'config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Vérifie si le produit est déjà dans le panier
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);

if ($stmt->fetch()) {
    // Incrémente la quantité si l'article existe déjà
    $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?")
        ->execute([$user_id, $product_id]);
} else {
    // Sinon, ajoute l'article avec une quantité de 1
    $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)")
        ->execute([$user_id, $product_id]);
}

header('Location: index.php');
exit();
