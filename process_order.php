<?php
// process_order.php
// Ce fichier est appelé par les boutons "Valider" ou "Refuser" de la page admin_orders.php.
// Il applique la décision de l'administrateur sur une demande d'adoption.

session_start();
require 'config.php';

// ---------------------------------------------------------------
// Vérification des droits : seul un administrateur peut agir
// ---------------------------------------------------------------
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) {
    header('Location: index.php');
    exit();
}

// ---------------------------------------------------------------
// Récupération et validation des données POST
// ---------------------------------------------------------------
$request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$user_id    = isset($_POST['user_id'])    ? (int)$_POST['user_id']    : 0;
$action     = isset($_POST['action'])     ? $_POST['action']          : '';

// Vérification que toutes les données nécessaires sont présentes
if (!$request_id || !$product_id || !$user_id || !in_array($action, ['valider', 'refuser'])) {
    header('Location: admin_orders.php');
    exit();
}

// ---------------------------------------------------------------
// On vérifie que la demande existe bien et est encore en attente.
// Cela évite de traiter deux fois la même demande par erreur.
// ---------------------------------------------------------------
$check = $pdo->prepare("SELECT status FROM purchase_requests WHERE id = ?");
$check->execute([$request_id]);
$current_status = $check->fetchColumn();

if ($current_status !== 'en_attente') {
    // La demande a déjà été traitée, on redirige sans rien faire
    header('Location: admin_orders.php');
    exit();
}

// ---------------------------------------------------------------
// Application de la décision de l'admin
// ---------------------------------------------------------------
if ($action === 'valider') {

    // --- CAS 1 : VALIDATION ---
    // 1a. Mettre à jour le statut de la demande à "validee"
    $pdo->prepare("UPDATE purchase_requests SET status = 'validee' WHERE id = ?")
        ->execute([$request_id]);

    // 1b. Marquer l'animal comme vendu (is_sold = 1).
    //     Cela le masquera dans la boutique pour éviter de nouvelles demandes.
    $pdo->prepare("UPDATE products SET is_sold = 1 WHERE id = ?")
        ->execute([$product_id]);

    // 1c. Retirer cet animal des paniers de TOUS les utilisateurs.
    //     En effet, plusieurs utilisateurs pourraient avoir le même animal
    //     dans leur panier en même temps — ils devront choisir un autre animal.
    $pdo->prepare("DELETE FROM cart_items WHERE product_id = ?")
        ->execute([$product_id]);

    // 1d. Refuser automatiquement les autres demandes en attente pour le même animal.
    //     Un seul utilisateur peut adopter un même animal.
    $pdo->prepare("
        UPDATE purchase_requests
        SET status = 'refusee'
        WHERE product_id = ? AND id != ? AND status = 'en_attente'
    ")->execute([$product_id, $request_id]);

} elseif ($action === 'refuser') {

    // --- CAS 2 : REFUS ---
    // Mettre à jour le statut de la demande à "refusee".
    // L'animal reste disponible pour d'autres adoptions.
    $pdo->prepare("UPDATE purchase_requests SET status = 'refusee' WHERE id = ?")
        ->execute([$request_id]);
}

// Redirection vers la liste des demandes après traitement
header('Location: admin_orders.php');
exit();
?>
