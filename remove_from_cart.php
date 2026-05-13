<?php
// remove_from_cart.php (Gère maintenant toutes les modifications du panier : +, -, supprimer, vider)
session_start();
require 'config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? 'remove'; // remove, decrement, increment, clear

if ($action === 'clear') {
    // Vider complètement le panier
    $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);
} elseif ($product_id) {
    // Action = 'remove' : Supprimer toute la ligne du panier d'un coup
    // Les actions 'increment' et 'decrement' ont été supprimées car un animal ne peut être acheté qu'une seule fois
    $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?")
        ->execute([$user_id, $product_id]);
}
header('Location: cart.php');
exit();
