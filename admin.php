<?php
// admin.php
// Panneau d'administration principal.
// Accessible uniquement aux utilisateurs ayant le rôle administrateur.

session_start();
require 'config.php';

// Vérification de la connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérification du rôle administrateur
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$isAdmin = $stmt->fetchColumn();

if (!$isAdmin) {
    header('Location: index.php');
    exit();
}

// Comptage des demandes d'adoption en attente pour afficher un badge sur la carte
$pending_stmt = $pdo->query("SELECT COUNT(*) FROM purchase_requests WHERE status = 'en_attente'");
$pending_count = $pending_stmt->fetchColumn();

include 'includes/header.php';
?>

<h1 style="margin-bottom: 2rem;">Panneau d'Administration</h1>

<div class="product-grid">

    <!-- Carte 1 : Gestion des animaux (ajouter, supprimer) -->
    <a href="admin_products.php" style="text-decoration: none;">
        <div class="card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🐾</div>
            <h3>Gérer les animaux</h3>
            <p>Ajouter ou supprimer des animaux de la boutique.</p>
        </div>
    </a>

    <!-- Carte 2 : Gestion des utilisateurs inscrits -->
    <a href="admin_users.php" style="text-decoration: none;">
        <div class="card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
            <h3>Gérer les utilisateurs</h3>
            <p>Voir la liste des membres inscrits.</p>
        </div>
    </a>

    <!-- Carte 3 : Demandes d'adoption en attente de validation -->
    <!-- Un badge rouge indique le nombre de demandes non traitées -->
    <a href="admin_orders.php" style="text-decoration: none;">
        <div class="card" style="text-align: center; padding: 3rem; position: relative;">
            <?php if ($pending_count > 0): ?>
                <!-- Badge affiché uniquement s'il y a des demandes en attente -->
                <span class="badge" style="position: absolute; top: 1rem; right: 1rem; font-size: 1rem;">
                    <?= $pending_count ?>
                </span>
            <?php endif; ?>
            <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
            <h3>Demandes d'adoption</h3>
            <p>Valider ou refuser les demandes soumises par les utilisateurs.</p>
        </div>
    </a>


</div>

<?php include 'includes/footer.php'; ?>
