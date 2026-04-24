<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$result = $pdo->query("SELECT * FROM products");

include 'includes/header.php';
?>

<header class="page-header">
    <h1>Découvrez nos animaux</h1>
    <p>Trouvez le compagnon parfait pour votre famille.</p>
</header>

<div class="product-grid">
    <?php while($row = $result->fetch()) { ?>
        <div class="card">
            <!-- Vérification et affichage de l'image de l'animal depuis la base de données -->
            <?php if (!empty($row['image'])): ?>
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="card-img" style="width: 100%; border-top-left-radius: 8px; border-top-right-radius: 8px; height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-content">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= htmlspecialchars($row['description']) ?></p>
            </div>
            <div class="price"><?= number_format($row['price'], 2) ?> EUR</div>
            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn-outline">Ajouter au panier</button>
            </form>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
