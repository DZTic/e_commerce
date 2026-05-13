<?php
session_start();
require 'config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si ce n'est pas une requête AJAX, on redirige
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['error' => 'Non connecté']);
        exit();
    }
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Vérifie si le produit est déjà dans le panier
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);

if ($stmt->fetch()) {
    // Un animal est unique, on ne peut l'acheter qu'une seule fois.
    // Donc si l'article existe déjà dans le panier, on ne l'incrémente pas.
} else {
    // Vérifie si le produit est déjà vendu (par mesure de sécurité) avant de l'ajouter
    $stmt_sold = $pdo->prepare("SELECT is_sold FROM products WHERE id = ?");
    $stmt_sold->execute([$product_id]);
    $is_sold = $stmt_sold->fetchColumn();

    if (!$is_sold) {
        // Sinon, ajoute l'article avec une quantité fixe de 1
        $pdo->prepare("INSERT INTO cart_items (user_id, product_id) VALUES (?, ?)")
            ->execute([$user_id, $product_id]);
    }
}

// Calcul du nouveau total d'articles pour la réponse AJAX
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_items = $stmt->fetchColumn() ?: 0;

// Si c'est une requête AJAX (détectée via l'en-tête X-Requested-With ou si on décide de tout passer en JSON)
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'total_items' => $total_items,
        'message' => 'Produit ajouté au panier !'
    ]);
    exit();
}

// Comportement par défaut (fallback si JS est désactivé)
header('Location: index.php');
exit();

