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

<!-- Section des Filtres -->
<div class="filters-container">
    <div class="filters-group">
        <strong>Catégories :</strong>
        <a href="index.php" class="filter-btn <?= !$category_id ? 'active' : '' ?>">Tous</a>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?category=<?= $cat['id'] ?>" class="filter-btn <?= $category_id == $cat['id'] ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($category_id && !empty($subcategories)): ?>
        <div class="subcategories-container">
            <strong>Sous-catégories :</strong>
            <a href="index.php?category=<?= $category_id ?>" class="subcategory-btn <?= !$subcategory_id ? 'active' : '' ?>">Tous</a>
            <?php foreach ($subcategories as $sub): ?>
                <a href="index.php?category=<?= $category_id ?>&subcategory=<?= $sub['id'] ?>" class="subcategory-btn <?= $subcategory_id == $sub['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($sub['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="product-grid">
    <?php foreach($products as $row) { ?>
        <!-- On ajoute un curseur pointeur et un événement au clic pour ouvrir la pop-up -->
        <div class="card" onclick="openAnimalDetails(<?= $row['id'] ?>)" style="cursor: pointer;">
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
            
            <!-- Le formulaire d'ajout au panier. stopPropagation() empêche l'ouverture de la pop-up lors du clic sur le bouton -->
            <form method="post" action="add_to_cart.php" onclick="event.stopPropagation();">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn-outline">Ajouter au panier</button>
            </form>
        </div>
    <?php } ?>
</div>

<?php 
// Inclusion de la structure de la pop-up et de sa logique JavaScript
include 'includes/modal_container.php'; 
?>

<?php include 'includes/footer.php'; ?>
