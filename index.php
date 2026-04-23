<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$result = $pdo->query("SELECT * FROM products");
?>

<header style="margin-bottom: 3rem; text-align: center;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">Découvrez nos jeux</h1>
    <p style="color: var(--text-muted);">Les meilleures aventures vous attendent.</p>
</header>

<div class="product-grid">
    <?php while($row = $result->fetch()) { ?>
        <div class="card">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <div class="price"><?= number_format($row['price'], 2) ?> EUR</div>
            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <button type="submit">Ajouter au panier</button>
            </form>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
