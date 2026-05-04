<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$isAdmin = $stmt->fetchColumn();

if (!$isAdmin) {
    header('Location: index.php');
    exit();
}

include 'includes/header.php';
?>

<h1 style="margin-bottom: 2rem;">Panneau d'Administration</h1>

<div class="product-grid">
    <a href="admin_products.php" style="text-decoration: none;">
        <div class="card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🖥️</div>
            <h3 style="color: white;">Gérer les produits</h3>
            <p>Ajouter, modifier ou supprimer des jeux.</p>
        </div>
    </a>
    <a href="admin_users.php" style="text-decoration: none;">
        <div class="card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
            <h3 style="color: white;">Gérer les utilisateurs</h3>
            <p>Voir la liste des membres inscrits.</p>
        </div>
    </a>

</div>

<?php include 'includes/footer.php'; ?>
