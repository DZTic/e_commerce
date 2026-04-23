<?php
include 'includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
    $stmt->execute([$_POST['username'], $hash]);
    header("Location: login.php");
    exit();
}
?>

<div class="auth-container">
    <h2 style="margin-bottom: 2rem; text-align: center; font-weight: 400; font-size: 1.8rem;">Rejoignez GameVault</h2>
    
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Créer mon compte</button>
    </form>
    
    <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.85rem;">
        Déjà inscrit ? <a href="login.php" style="color: var(--text-main); text-decoration: none; font-weight: 500;">Se connecter</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>
