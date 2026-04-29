<?php
/**
 * Ce fichier gère l'affichage des détails d'un animal pour la pop-up.
 * Il récupère les informations de la base de données en fonction de l'ID passé en paramètre.
 */
require 'config.php';

// On récupère l'ID de l'animal depuis la requête GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Préparation de la requête pour récupérer l'animal spécifique
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $animal = $stmt->fetch();

    if ($animal) {
        // Affichage du contenu de la pop-up
        ?>
        <div class="modal-header">
            <?php if (!empty($animal['image'])): ?>
                <img src="<?= htmlspecialchars($animal['image']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>" class="modal-header-img">
            <?php endif; ?>
        </div>
        <div class="modal-body">
            <h2 class="modal-title"><?= htmlspecialchars($animal['name']) ?></h2>
            <div class="price"><?= number_format($animal['price'], 2) ?> EUR</div>
            
            <div class="modal-description">
                <?= nl2br(htmlspecialchars($animal['description'])) ?>
            </div>

            <!-- Informations complémentaires issues de la base de données -->
            <h3>Informations complémentaires</h3>
            <ul class="modal-details-list">
                <li><strong>Âge :</strong> <?= htmlspecialchars($animal['age'] ?? 'Inconnu') ?> ans</li>
                <li><strong>Santé :</strong> <?= htmlspecialchars($animal['health'] ?? 'Non spécifiée') ?></li>
                <li><strong>Caractère :</strong> <?= htmlspecialchars($animal['character'] ?? 'Non spécifié') ?></li>
                <li><strong>Disponibilité :</strong> <?= htmlspecialchars($animal['availability'] ?? 'Inconnue') ?></li>
            </ul>

            <form method="post" action="add_to_cart.php" style="margin-top: 30px;">
                <input type="hidden" name="product_id" value="<?= $animal['id'] ?>">
                <button type="submit" class="btn-outline">Adopter maintenant</button>
            </form>
        </div>
        <?php
    } else {
        echo "<p>Animal non trouvé.</p>";
    }
} else {
    echo "<p>ID invalide.</p>";
}
?>
