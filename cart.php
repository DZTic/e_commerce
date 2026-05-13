<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT SUM(p.price) as total_price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->execute([$user_id]);
$total_price = $stmt->fetchColumn() ?: 0;

include 'includes/header.php';
?>

<div class="page-header" style="text-align: left; margin: 3rem 0;">
    <h1 style="font-size: 2rem;">Votre Panier</h1>
</div>

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
                    <td><span style="font-weight: 500;"><?= htmlspecialchars($item['name']) ?></span></td>
                    <td><?= number_format($item['price'], 2) ?> EUR</td>
                    <td>
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <!-- La quantité est toujours de 1 car chaque animal est unique -->
                            <span style="font-size: 1.1rem; font-weight: bold; min-width: 20px; text-align: center;">1</span>
                        </div>
                    </td>
                    <td><span style="color: var(--text-main); font-weight: 500;"><?= number_format($item['price'], 2) ?> EUR</span></td>
                    <td>
                        <form method="post" action="remove_from_cart.php">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="btn-outline" style="width: auto; padding: 0.4rem 0.8rem; font-size: 0.7rem; color: #D32F2F; border-color: #FFCDD2; background: #FFEBEE;">Supprimer</button>
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; flex-wrap: wrap; gap: 1rem;">
        
        <!-- Bouton Tout retirer (Vider le panier) -->
        <form method="post" action="remove_from_cart.php" style="margin: 0;">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="btn-clear" style="padding: 0.8rem 1.5rem; font-size: 0.9rem;">
                Vider le panier
            </button>
        </form>

        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="font-size: 1.2rem;">Total : <span style="font-weight: 600; color: var(--text-main);"><?= number_format($total_price, 2) ?> EUR</span></div>
            <form method="post" action="validate_order.php" style="margin: 0;">
                <button type="submit" style="width: auto; padding: 1rem 2.5rem; font-size: 1rem;">
                    Valider la commande
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>

<div style="margin-top: 2rem;">
    <a href="index.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Retour à la boutique</a>
</div>

<?php include 'includes/footer.php'; ?>
