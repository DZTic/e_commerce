<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$result = $pdo->query("SELECT * FROM products");
?>

<header class="page-header">
    <h1>Découvrez nos jeux</h1>
    <p>Les meilleures aventures vous attendent.</p>
</header>

<div class="product-grid">
    <?php while($row = $result->fetch()) { ?>
        <div class="card">
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
