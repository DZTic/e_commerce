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
