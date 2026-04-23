<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/config.php';

// Calcul du total du panier pour le badge
$cart_badges = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cart_badges = $stmt->fetchColumn() ?: 0;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameVault - Boutique</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav>
        <div class="logo">GameVault</div>
        <div>
            <a href="index.php">Boutique</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cart.php">Panier <span class="badge"><?= $cart_badges ?></span></a>
                <?php 
                // Check if admin
                $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                if ($stmt->fetchColumn()): ?>
                    <a href="admin.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="login.php">Connexion</a>
                <a href="register.php">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
