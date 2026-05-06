<?php
// Démarrage de la session pour accéder aux variables de session (par exemple, pour afficher le nom de l'utilisateur ou gérer le panier)
session_start();

// Inclusion du fichier de configuration, souvent utilisé pour la connexion à la base de données
require 'config.php';

// Inclusion de l'en-tête de la page (qui contient généralement les balises head, le menu de navigation, etc.)
include 'includes/header.php';
?>

<!-- Conteneur principal pour le message de confirmation, centré avec des espacements -->
<div style="text-align: center; padding: 5rem 0;">
    <!-- Icône de validation visuelle (coche) -->
    <div style="font-size: 5rem; margin-bottom: 2rem;">✅</div>
    
    <!-- Titre principal remerciant le client -->
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Merci pour votre commande !</h1>
    
    <!-- Message détaillé confirmant l'enregistrement de la commande -->
    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 3rem;">
        Votre commande a été enregistrée avec succès. Un e-mail de confirmation vous a été envoyé.
    </p>
    
    <!-- Lien permettant au client de retourner à la page d'accueil pour continuer ses achats -->
    <a href="index.php">
        <!-- Bouton stylisé avec des bords arrondis pour une esthétique moderne -->
        <button style="width: auto; padding: 1rem 2rem; border-radius: 99px;">Continuer mes achats</button>
    </a>
</div>

<?php 
// Inclusion du pied de page (qui contient généralement les liens de bas de page, copyrights, etc.)
include 'includes/footer.php'; 
?>