<?php
/**
 * Ce fichier gère l'affichage des détails d'un animal pour la pop-up.
 * Il récupère les informations de la base de données en fonction de l'ID passé en paramètre.
 */
session_start();
require 'config.php';

// On récupère l'ID de l'animal depuis la requête GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Préparation de la requête pour récupérer l'animal spécifique avec ses catégories
    $stmt = $pdo->prepare("SELECT p.*, s.name as subcategory_name, c.name as category_name 
                           FROM products p 
                           LEFT JOIN subcategories s ON p.subcategory_id = s.id 
                           LEFT JOIN categories c ON s.category_id = c.id 
                           WHERE p.id = ?");
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
                <li><strong>Catégorie :</strong> <?= htmlspecialchars($animal['category_name'] ?? 'Divers') ?></li>
                <li><strong>Espèce :</strong> <?= htmlspecialchars($animal['subcategory_name'] ?? 'Inconnu') ?></li>
                <li><strong>Âge :</strong> <?= htmlspecialchars($animal['age'] ?? 'Inconnu') ?> ans</li>
                <li><strong>Santé :</strong> <?= htmlspecialchars($animal['health'] ?? 'Non spécifiée') ?></li>
                <li><strong>Caractère :</strong> <?= htmlspecialchars($animal['character'] ?? 'Non spécifié') ?></li>
            </ul>

            <?php
            // On vérifie si l'utilisateur est connecté et si le produit est dans ses favoris ou son panier
            $is_favorite = false;
            $is_in_cart = false;
            if (isset($_SESSION['user_id'])) {
                $fav_stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
                $fav_stmt->execute([$_SESSION['user_id'], $animal['id']]);
                $is_favorite = $fav_stmt->fetch() !== false;

                $cart_stmt = $pdo->prepare("SELECT id FROM cart_items WHERE user_id = ? AND product_id = ?");
                $cart_stmt->execute([$_SESSION['user_id'], $animal['id']]);
                $is_in_cart = $cart_stmt->fetch() !== false;
            }
            ?>
            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <?php if ($animal['is_sold']): ?>
                    <button type="button" class="btn-outline" style="flex: 1; background-color: #ffe6e6; border-color: #ffcccc; color: #cc0000; cursor: not-allowed;" disabled>Déjà adopté</button>
                <?php elseif ($is_in_cart): ?>
                    <button type="button" class="btn-outline btn-disabled" style="flex: 1;" disabled>Déjà dans le panier</button>
                <?php else: ?>
                    <form method="post" action="add_to_cart.php" style="flex: 1;">
                        <input type="hidden" name="product_id" value="<?= $animal['id'] ?>">
                        <button type="submit" class="btn-outline" style="width: 100%;">Adopter maintenant</button>
                    </form>
                <?php endif; ?>
                
                <!-- Formulaire d'ajout aux favoris en AJAX dans la pop-up -->
                <button type="button" onclick="event.stopPropagation(); toggleFavorite(<?= $animal['id'] ?>)" id="btn-fav-<?= $animal['id'] ?>" class="btn-favorite <?= $is_favorite ? 'active' : '' ?>" style="background: <?= $is_favorite ? '#ffe6e6' : 'white' ?>; border: 3px solid var(--border); border-radius: 10px; font-size: 1.5rem; cursor: pointer; color: <?= $is_favorite ? 'red' : 'var(--text-main)' ?>; padding: 10px; height: 100%; box-shadow: var(--shadow); display: flex; align-items: center; justify-content: center; width: 60px; transition: all 0.1s ease;">
                    <svg id="svg-fav-<?= $animal['id'] ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="<?= $is_favorite ? 'red' : 'none' ?>" stroke="<?= $is_favorite ? 'red' : 'currentColor' ?>" stroke-width="2" style="width: 28px; height: 28px; transition: transform 0.2s;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            </div>
        </div>
        <?php
    } else {
        echo "<p>Animal non trouvé.</p>";
    }
} else {
    echo "<p>ID invalide.</p>";
}
?>
