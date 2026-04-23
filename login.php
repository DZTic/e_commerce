<?php
include 'includes/header.php';

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
?>

<div class="auth-container">
    <h2 style="margin-bottom: 2rem; text-align: center;">Connexion</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: var(--accent); background: rgba(244, 63, 94, 0.1); padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: center;">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    
    <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
        Pas encore de compte ? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">S'inscrire</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>
