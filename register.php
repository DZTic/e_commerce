<?php
/**
 * register.php
 * Gère l'inscription des nouveaux utilisateurs.
 */
session_start();
require 'config.php'; // Connexion à la base de données

$error = "";

// Traitement du formulaire lorsqu'il est soumis via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 1. Vérification de la disponibilité du nom d'utilisateur
    // On compte combien d'utilisateurs possèdent déjà ce pseudo
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userExists = $stmt->fetchColumn() > 0;

    if ($userExists) {
        // Si le pseudo est pris, on prépare un message d'erreur
        $error = "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // 2. Création du compte si le pseudo est libre
        // On hache le mot de passe pour la sécurité avant de l'enregistrer
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertion du nouvel utilisateur (is_admin est à 0 par défaut)
        $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
        $stmt->execute([$username, $hash]);
        
        // Redirection vers la page de connexion après succès
        header("Location: login.php");
        exit();
    }
}

// Inclusion de l'en-tête du site
include 'includes/header.php';
?>

<div class="auth-container">
    <h2 style="margin-bottom: 2rem; text-align: center; font-weight: 400; font-size: 1.8rem;">Rejoignez Cartoon's Animals</h2>
    
    <!-- Affichage du message d'erreur si le pseudo est déjà pris -->
    <?php if ($error): ?>
        <div style="background: #ffe6e6; color: #cc0000; padding: 1rem; border-radius: 10px; border: 1px solid #ffcccc; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <!-- Formulaire d'inscription -->
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Créer mon compte</button>
    </form>
    
    <!-- Lien vers la page de connexion pour les utilisateurs déjà inscrits -->
    <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.85rem;">
        Déjà inscrit ? <a href="login.php" style="color: var(--text-main); text-decoration: none; font-weight: 500;">Se connecter</a>
    </p>
</div>

<?php 
// Inclusion du pied de page du site
include 'includes/footer.php'; 
?>
