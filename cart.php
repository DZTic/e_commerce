<?php
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.price, ci.quantity
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT SUM(ci.quantity * p.price) as total_price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$total_price = $stmt->fetchColumn() ?: 0;
?>

<h1 style="margin-bottom: 2rem;">Votre Panier</h1>

<div class="card" style="padding: 0;">
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><span style="font-weight: 600;"><?= htmlspecialchars($item['name']) ?></span></td>
                    <td><?= number_format($item['price'], 2) ?> EUR</td>
                    <td><?= $item['quantity'] ?></td>
                    <td><span style="color: var(--primary); font-weight: 600;"><?= number_format($item['price'] * $item['quantity'], 2) ?> EUR</span></td>
                    <td>
                        <form method="post" action="remove_from_cart.php">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" style="width: auto; padding: 0.4rem 0.8rem; background: var(--accent); font-size: 0.8rem;">Retirer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-muted);">Votre panier est vide.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($items)): ?>
    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 2rem; margin-top: 2rem;">
        <div style="font-size: 1.5rem;">Total : <span style="font-weight: 800; color: var(--primary);"><?= number_format($total_price, 2) ?> EUR</span></div>
        <form method="post" action="validate_order.php">
            <button type="submit" style="width: auto; padding: 1rem 2.5rem; font-size: 1.1rem; border-radius: 99px; box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39);">
                Valider la commande
            </button>
        </form>
    </div>
<?php endif; ?>

<div style="margin-top: 2rem;">
    <a href="index.php" style="color: var(--text-muted); text-decoration: none;">&larr; Retour à la boutique</a>
</div>

<?php include 'includes/footer.php'; ?>
