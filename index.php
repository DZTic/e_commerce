<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupération des filtres
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$subcategory_id = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Construction de la requête avec filtres et recherche
$query = "SELECT p.*, s.name as subcategory_name, c.name as category_name 
          FROM products p 
          LEFT JOIN subcategories s ON p.subcategory_id = s.id 
          LEFT JOIN categories c ON s.category_id = c.id";

$conditions = [];
$params = [];

// Filtre par recherche textuelle (uniquement sur le nom)
if ($search) {
    $conditions[] = "p.name LIKE ?";
    $params[] = "%$search%";
}

// Filtre par sous-catégorie ou catégorie
if ($subcategory_id) {
    $conditions[] = "p.subcategory_id = ?";
    $params[] = $subcategory_id;
} elseif ($category_id) {
    $conditions[] = "s.category_id = ?";
    $params[] = $category_id;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Récupération des favoris et du panier de l'utilisateur connecté pour l'affichage des boutons
$user_favorites = [];
$user_cart = [];
if (isset($_SESSION['user_id'])) {
    $fav_stmt = $pdo->prepare("SELECT product_id FROM favorites WHERE user_id = ?");
    $fav_stmt->execute([$_SESSION['user_id']]);
    $user_favorites = $fav_stmt->fetchAll(PDO::FETCH_COLUMN);

    $cart_stmt = $pdo->prepare("SELECT product_id FROM cart_items WHERE user_id = ?");
    $cart_stmt->execute([$_SESSION['user_id']]);
    $user_cart = $cart_stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Récupération des catégories pour le filtre
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$subcategories = [];
if ($category_id) {
    $stmt = $pdo->prepare("SELECT * FROM subcategories WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $subcategories = $stmt->fetchAll();
}

include 'includes/header.php';
?>

<header class="page-header">
    <h1>Découvrez nos animaux</h1>
    <p>Trouvez le compagnon parfait pour votre famille.</p>
</header>

<?php 
// Inclusion de la barre de recherche créée précédemment
include 'nav.php'; 
?>

<?php include 'includes/filters.php'; ?>

<div class="product-grid">
    <?php foreach($products as $row) { 
        $is_favorite = in_array($row['id'], $user_favorites);
        $is_in_cart = in_array($row['id'], $user_cart);
    ?>
        <!-- On ajoute un curseur pointeur et un événement au clic pour ouvrir la pop-up -->
        <div class="card" onclick="openAnimalDetails(<?= $row['id'] ?>)" style="cursor: pointer; position: relative;">
            
            <!-- Petit cœur indicateur de favori sur l'image -->
            <div id="fav-indicator-<?= $row['id'] ?>" style="position: absolute; top: 15px; right: 15px; font-size: 1.5rem; display: <?= $is_favorite ? 'block' : 'none' ?>; pointer-events: none; text-shadow: 0px 0px 5px rgba(255, 255, 255, 0.8);">
                ❤️
            </div>
            <!-- Vérification et affichage de l'image de l'animal depuis la base de données -->
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
            
            <!-- Affichage du bouton selon l'état de l'animal -->
            <?php if ($row['is_sold']): ?>
                <button type="button" class="btn-outline" style="background-color: #ffe6e6; border-color: #ffcccc; color: #cc0000; cursor: not-allowed; width: 100%;" onclick="event.stopPropagation();" disabled>Déjà adopté</button>
            <?php elseif ($is_in_cart): ?>
                <button type="button" class="btn-outline btn-disabled" style="width: 100%;" onclick="event.stopPropagation();" disabled>Déjà dans le panier</button>
            <?php else: ?>
                <form method="post" action="add_to_cart.php" onclick="event.stopPropagation();">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-outline">Ajouter au panier</button>
                </form>
            <?php endif; ?>
        </div>
    <?php } ?>
</div>

<?php 
// Inclusion de la structure de la pop-up et de sa logique JavaScript
include 'includes/modal_container.php'; 
?>

<?php include 'includes/footer.php'; ?>
