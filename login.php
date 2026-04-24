<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <h2 style="margin-bottom: 2rem; text-align: center; font-weight: 400; font-size: 1.8rem;">Connexion</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: #d32f2f; background: #ffebee; padding: 0.75rem; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center; border: 1px solid #ffcdd2;">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    
    <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.85rem;">
        Pas encore de compte ? <a href="register.php" style="color: var(--text-main); text-decoration: none; font-weight: 500;">S'inscrire</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>
