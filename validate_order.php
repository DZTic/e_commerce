<?php
// validate_order.php
// Ce fichier est appelé quand l'utilisateur clique sur "Valider la commande" dans son panier.
// NOUVEAU COMPORTEMENT : au lieu de traiter l'achat immédiatement, on crée une demande
// en attente (statut "en_attente") que l'administrateur devra valider manuellement.

session_start();
require 'config.php';

// Vérification de sécurité : l'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// ---------------------------------------------------------------
// Étape 1 : Récupérer tous les animaux du panier de l'utilisateur
// avec leur prix, pour créer une demande par animal.
// ---------------------------------------------------------------
$stmt = $pdo->prepare("
    SELECT ci.product_id, p.price, p.name
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

// S'il n'y a rien dans le panier, on redirige sans rien faire
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// ---------------------------------------------------------------
// Étape 2 : Pour chaque animal du panier, créer une ligne dans
// la table purchase_requests avec le statut "en_attente".
// L'administrateur devra ensuite valider ou refuser chaque demande.
// ---------------------------------------------------------------
$insert_request = $pdo->prepare("
    INSERT INTO purchase_requests (user_id, product_id, total_price, status, created_at)
    VALUES (?, ?, ?, 'en_attente', datetime('now'))
");

foreach ($cart_items as $item) {
    $insert_request->execute([
        $user_id,
        $item['product_id'],
        $item['price']
    ]);
}

// ---------------------------------------------------------------
// Étape 3 : Vider le panier de l'utilisateur.
// Les animaux ne sont PAS encore marqués comme vendus — cela se
// fera uniquement quand l'admin valide la demande.
// ---------------------------------------------------------------
$pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$user_id]);

// Redirection vers la page de confirmation
header("Location: confirmation.php");
exit();
?>
