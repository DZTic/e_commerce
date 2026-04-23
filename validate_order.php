<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];

// Calculer le total du panier
$stmt = $pdo->prepare("
    SELECT SUM(ci.quantity * p.price) as total_price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$total_price = $stmt->fetchColumn();
$total_price = $total_price ?: 0;

// Insérer la commande dans la table orders
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total, created_at) VALUES (?, ?, datetime('now'))");
$stmt->execute([$user_id, $total_price]);

// Vider le panier
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);

header("Location: confirmation.php");
exit();
