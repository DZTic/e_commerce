<?php
// favorites.php
// Ce fichier affiche la liste des produits que l'utilisateur a ajoutés à ses favoris.

session_start();
require 'config.php';

// Redirige vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// On récupère les produits qui sont dans les favoris de l'utilisateur
// en effectuant une jointure (JOIN) entre la table products et la table favorites
$query = "SELECT p.*, s.name as subcategory_name, c.name as category_name 
          FROM products p 
          INNER JOIN favorites f ON p.id = f.product_id
          LEFT JOIN subcategories s ON p.subcategory_id = s.id 
          LEFT JOIN categories c ON s.category_id = c.id
          WHERE f.user_id = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$products = $stmt->fetchAll();

// On récupère également la liste des IDs favoris pour savoir quoi afficher (bien qu'ici ils le soient tous)
$user_favorites = array_column($products, 'id');

// Récupération du panier de l'utilisateur
$user_cart = [];
if (isset($_SESSION['user_id'])) {
    $cart_stmt = $pdo->prepare("SELECT product_id FROM cart_items WHERE user_id = ?");
    $cart_stmt->execute([$_SESSION['user_id']]);
    $user_cart = $cart_stmt->fetchAll(PDO::FETCH_COLUMN);
}

include 'includes/header.php';
?>

<header class="page-header">
    <h1>Mes Favoris</h1>
    <p>Retrouvez ici tous les animaux qui ont fait fondre votre cœur.</p>
</header>

<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>Vous n'avez pas encore de favoris. <a href="index.php">Découvrez nos animaux</a></p>
    <?php else: ?>
        <?php foreach($products as $row) { 
            $is_in_cart = in_array($row['id'], $user_cart);
        ?>
            <!-- L'événement au clic ouvre la pop-up avec les détails de l'animal -->
            <div class="card" onclick="openAnimalDetails(<?= $row['id'] ?>)" style="cursor: pointer;">
                
                <?php if (!empty($row['image'])): ?>
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="card-img" style="width: 100%; border-top-left-radius: 8px; border-top-right-radius: 8px; height: 200px; object-fit: cover;">
                <?php endif; ?>
                
                <div class="card-content">
                    <div style="font-size: 0.8rem; color: var(--primary); font-weight: bold; text-transform: uppercase; margin-bottom: 5px;">
                        <?= htmlspecialchars($row['category_name'] ?? 'Divers') ?> / <?= htmlspecialchars($row['subcategory_name'] ?? 'Inconnu') ?>
                    </div>
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                </div>
                <div class="price"><?= number_format($row['price'], 2) ?> EUR</div>
                
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <!-- Le formulaire d'ajout au panier ou l'indication -->
                    <?php if ($row['is_sold']): ?>
                        <button type="button" class="btn-outline" style="flex: 1; background-color: #ffe6e6; border-color: #ffcccc; color: #cc0000; cursor: not-allowed;" onclick="event.stopPropagation();" disabled>Déjà adopté</button>
                    <?php elseif ($is_in_cart): ?>
                        <button type="button" class="btn-outline btn-disabled" style="flex: 1;" onclick="event.stopPropagation();" disabled>Déjà dans le panier</button>
                    <?php else: ?>
                        <form method="post" action="add_to_cart.php" onclick="event.stopPropagation();" style="flex: 1;">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn-outline" style="width: 100%;">Ajouter au panier</button>
                        </form>
                    <?php endif; ?>

                    <!-- Bouton pour retirer des favoris en AJAX -->
                    <button type="button" onclick="event.stopPropagation(); toggleFavorite(<?= $row['id'] ?>)" id="btn-fav-<?= $row['id'] ?>" class="btn-favorite active" style="background: #ffe6e6; border: 3px solid var(--border); border-radius: 10px; cursor: pointer; color: red; padding: 10px; height: 100%; box-shadow: var(--shadow); display: flex; align-items: center; justify-content: center; width: 60px; transition: all 0.1s ease;">
                        <svg id="svg-fav-<?= $row['id'] ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="red" stroke="red" stroke-width="2" style="width: 28px; height: 28px; transition: transform 0.2s;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>
            </div>
        <?php } ?>
    <?php endif; ?>
</div>

<?php 
// Inclusion de la structure de la pop-up et de sa logique JavaScript
include 'includes/modal_container.php'; 
?>

<?php include 'includes/footer.php'; ?>
