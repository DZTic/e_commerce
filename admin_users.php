<?php
session_start();
require 'config.php';

// Security Check
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); }

$users = $pdo->query("SELECT id, username, is_admin FROM users")->fetchAll();

include 'includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 2rem;">
    <h1>Gestion des Utilisateurs</h1>
    <a href="admin.php" style="color: var(--text-muted); text-decoration: none;">&larr; Retour Admin</a>
</div>

<div class="card" style="padding: 0;">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><span style="font-weight: 600;"><?= htmlspecialchars($u['username']) ?></span></td>
                    <td>
                        <span class="badge" style="background: <?= $u['is_admin'] ? 'var(--primary)' : 'var(--bg-dark)' ?>;">
                            <?= $u['is_admin'] ? 'Admin' : 'Client' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <a href="delete_users.php?id=<?= $u['id'] ?>" style="color: var(--accent); text-decoration: none; font-size: 0.9rem;">Supprimer</a>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">(Vous)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
