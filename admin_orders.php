<?php
// admin_orders.php
// Page réservée aux administrateurs pour gérer les demandes d'adoption soumises par les utilisateurs.
// L'admin peut ici VALIDER ou REFUSER chaque demande en attente.
// - Valider : l'animal est marqué comme vendu et retiré des autres paniers
// - Refuser  : la demande est rejetée et l'animal reste disponible

session_start();
require 'config.php';

// ---------------------------------------------------------------
// Vérification des droits : seul un administrateur peut accéder
// ---------------------------------------------------------------
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) {
    header('Location: index.php');
    exit();
}

// ---------------------------------------------------------------
// Récupération de toutes les demandes d'achat avec les infos
// de l'utilisateur et de l'animal concerné, triées par date
// ---------------------------------------------------------------
$stmt = $pdo->query("
    SELECT
        pr.id           AS request_id,
        pr.status,
        pr.total_price,
        pr.created_at,
        u.id            AS user_id,
        u.username      AS user_name,
        p.id            AS product_id,
        p.name          AS product_name,
        p.image         AS product_image
    FROM purchase_requests pr
    JOIN users u    ON pr.user_id    = u.id
    JOIN products p ON pr.product_id = p.id
    ORDER BY
        CASE pr.status WHEN 'en_attente' THEN 0 ELSE 1 END, -- en attente en premier
        pr.created_at DESC
");
$requests = $stmt->fetchAll();

// Comptage des demandes en attente pour l'affichage
$pending_count = count(array_filter($requests, fn($r) => $r['status'] === 'en_attente'));

include 'includes/header.php';
?>

<!-- En-tête de la page avec lien retour -->
<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2rem;">
    <h1>Demandes d'adoption
        <?php if ($pending_count > 0): ?>
            <!-- Badge indiquant le nombre de demandes en attente -->
            <span class="badge" style="font-size: 1rem; vertical-align: middle; margin-left: 0.5rem;">
                <?= $pending_count ?>
            </span>
        <?php endif; ?>
    </h1>
    <a href="admin.php" style="color: var(--text-muted); text-decoration: none;">&larr; Retour Admin</a>
</div>

<!-- Tableau listant toutes les demandes -->
<div class="card" style="padding: 0;">
    <table>
        <thead>
            <tr>
                <th>Animal</th>
                <th>Utilisateur</th>
                <th>Prix</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($requests)): ?>
                <!-- Message si aucune demande n'existe -->
                <tr>
                    <td colspan="6" style="text-align:center; padding: 3rem; color: var(--text-muted);">
                        Aucune demande d'adoption pour le moment.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <!-- Colonne : nom de l'animal avec sa miniature -->
                        <td style="display: flex; align-items: center; gap: 0.8rem;">
                            <?php if (!empty($req['product_image'])): ?>
                                <img src="<?= htmlspecialchars($req['product_image']) ?>"
                                     alt="<?= htmlspecialchars($req['product_name']) ?>"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid var(--border);">
                            <?php endif; ?>
                            <strong><?= htmlspecialchars($req['product_name']) ?></strong>
                        </td>

                        <!-- Colonne : email de l'utilisateur qui a fait la demande -->
                        <td><?= htmlspecialchars($req['user_name']) ?></td>

                        <!-- Colonne : prix de l'adoption -->
                        <td><?= number_format($req['total_price'], 2) ?> EUR</td>

                        <!-- Colonne : date de soumission de la demande -->
                        <td><?= htmlspecialchars($req['created_at']) ?></td>

                        <!-- Colonne : badge de statut coloré selon l'état -->
                        <td>
                            <?php if ($req['status'] === 'en_attente'): ?>
                                <span style="background: #FFF3CD; color: #856404; border: 2px solid #FFECB5;
                                             border-radius: 20px; padding: 0.3rem 0.8rem; font-size: 0.85rem; font-weight: bold;">
                                    ⏳ En attente
                                </span>
                            <?php elseif ($req['status'] === 'validee'): ?>
                                <span style="background: #D4EDDA; color: #155724; border: 2px solid #C3E6CB;
                                             border-radius: 20px; padding: 0.3rem 0.8rem; font-size: 0.85rem; font-weight: bold;">
                                    ✅ Validée
                                </span>
                            <?php else: ?>
                                <span style="background: #F8D7DA; color: #721C24; border: 2px solid #F5C6CB;
                                             border-radius: 20px; padding: 0.3rem 0.8rem; font-size: 0.85rem; font-weight: bold;">
                                    ❌ Refusée
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Colonne : boutons d'action (seulement pour les demandes en attente) -->
                        <td>
                            <?php if ($req['status'] === 'en_attente'): ?>
                                <div style="display: flex; gap: 0.5rem;">

                                    <!-- Bouton VALIDER : approuve la demande et marque l'animal comme vendu -->
                                    <form method="post" action="process_order.php">
                                        <input type="hidden" name="request_id"  value="<?= $req['request_id'] ?>">
                                        <input type="hidden" name="product_id"  value="<?= $req['product_id'] ?>">
                                        <input type="hidden" name="user_id"     value="<?= $req['user_id'] ?>">
                                        <input type="hidden" name="action"      value="valider">
                                        <button type="submit"
                                                style="width: auto; padding: 0.4rem 1rem; font-size: 0.85rem;
                                                       background: #28a745; border-color: #1e7e34;">
                                            ✅ Valider
                                        </button>
                                    </form>

                                    <!-- Bouton REFUSER : rejette la demande, l'animal reste disponible -->
                                    <form method="post" action="process_order.php">
                                        <input type="hidden" name="request_id"  value="<?= $req['request_id'] ?>">
                                        <input type="hidden" name="product_id"  value="<?= $req['product_id'] ?>">
                                        <input type="hidden" name="user_id"     value="<?= $req['user_id'] ?>">
                                        <input type="hidden" name="action"      value="refuser">
                                        <button type="submit"
                                                style="width: auto; padding: 0.4rem 1rem; font-size: 0.85rem;
                                                       background: #dc3545; border-color: #c82333;">
                                            ❌ Refuser
                                        </button>
                                    </form>

                                </div>
                            <?php else: ?>
                                <!-- Demande déjà traitée : aucun bouton disponible -->
                                <span style="color: var(--text-muted); font-size: 0.85rem;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
