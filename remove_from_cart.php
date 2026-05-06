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
    if ($action === 'increment') {
        // Ajouter +1 à la quantité
        $pdo->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?")
            ->execute([$user_id, $product_id]);
    } elseif ($action === 'decrement') {
        // Retirer le produit 1 par 1
        $stmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $qty = $stmt->fetchColumn();

        if ($qty > 1) {
            // S'il y a plus d'1 quantité, on décrémente
            $pdo->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?")
                ->execute([$user_id, $product_id]);
        } else {
            // Sinon on supprime la ligne
            $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?")
                ->execute([$user_id, $product_id]);
        }
    } else {
        // Action = 'remove' : Supprimer toute la ligne du panier d'un coup
        $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?")
            ->execute([$user_id, $product_id]);
    }
}header('Location: cart.php');
exit();
