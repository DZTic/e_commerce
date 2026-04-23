<?php
session_start();
require 'config.php';

// Vérifie que l'utilisateur est connecté et que l'ID du produit est présent
if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Supprime l'article du panier
$pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?")
    ->execute([$user_id, $product_id]);

header('Location: cart.php');
exit();
