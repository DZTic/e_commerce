<?php
include 'includes/header.php';

// Security Check
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); }

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2rem;">
    <h1>Gestion des Produits</h1>
    <a href="admin.php" style="color: var(--text-muted); text-decoration: none;">&larr; Retour Admin</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
    <!-- Liste -->
    <div class="card" style="padding: 0;">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format($p['price'], 2) ?> EUR</td>
                        <td>
                            <a href="delete_products.php?id=<?= $p['id'] ?>" style="color: var(--accent); text-decoration: none; font-size: 0.9rem;">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Nouveau Produit</h3>
        <form method="post" action="add_products.php">
            <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Nom du jeu</label>
            <input type="text" name="name" placeholder="Ex: Elden Ring" required>
            
            <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Description</label>
            <input type="text" name="description" placeholder="Courte description..." required>
            
            <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Prix (EUR)</label>
            <input type="number" step="0.01" name="price" placeholder="59.99" required>
            
            <button type="submit">Ajouter le produit</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
