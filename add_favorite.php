<?php
// add_favorite.php
// Ce fichier gère l'ajout et la suppression des favoris.
// Il peut être appelé via AJAX ou via un formulaire classique.

session_start();
require 'config.php';

// Vérification que l'utilisateur est bien connecté
if (!isset($_SESSION['user_id'])) {
    // Si la requête est une requête AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['error' => 'Non connecté']);
        exit();
    }
    // Sinon redirection classique
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    echo json_encode(['error' => 'ID produit invalide']);
    exit();
}

// On vérifie si le produit est déjà dans les favoris de l'utilisateur
$stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$favorite = $stmt->fetch();

$is_now_favorite = false;

if ($favorite) {
    // Si le produit est déjà en favori, on le retire (Toggle)
    $stmtDelete = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmtDelete->execute([$user_id, $product_id]);
    $message = 'Produit retiré des favoris';
} else {
    // S'il n'est pas en favori, on l'ajoute
    $stmtInsert = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmtInsert->execute([$user_id, $product_id]);
    $is_now_favorite = true;
    $message = 'Produit ajouté aux favoris';
}

// Réponse pour les requêtes AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'is_favorite' => $is_now_favorite,
        'message' => $message
    ]);
    exit();
}

// Redirection classique si ce n'est pas de l'AJAX
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header('Location: ' . $redirect);
exit();
