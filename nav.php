<?php
/**
 * Ce fichier contient la barre de recherche globale du site.
 * Le style cartoon est appliqué via les classes CSS définies dans style.css.
 */
?>
<div class="search-bar-container">
    <form action="index.php" method="GET" class="search-form">
        <!-- Champ de saisie pour la recherche par nom uniquement -->
        <input type="text" 
               name="search" 
               placeholder="🔍 Quel animal recherchez-vous ? (Recherche par nom uniquement)" 
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        
        <!-- Bouton de validation de la recherche -->
        <button type="submit" class="search-btn">Chercher</button>
        
        <!-- Bouton de réinitialisation : n'apparaît que si une recherche est active -->
        <?php if (!empty($_GET['search'])): ?>
            <a href="index.php" class="btn-clear">❌ Annuler</a>
        <?php endif; ?>
    </form>
</div>