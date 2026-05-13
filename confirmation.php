<?php
// confirmation.php
// Page affichée après qu'un utilisateur a soumis une demande d'adoption.
// La demande est maintenant EN ATTENTE de validation par un administrateur.

session_start();
require 'config.php';
include 'includes/header.php';
?>

<!-- Conteneur principal pour le message de confirmation, centré avec des espacements -->
<div style="text-align: center; padding: 5rem 0;">

    <!-- Icône d'horloge pour signifier que la demande est en attente -->
    <div style="font-size: 5rem; margin-bottom: 2rem;">⏳</div>
    
    <!-- Titre principal -->
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Demande envoyée !</h1>
    
    <!-- Message expliquant le nouveau processus de validation -->
    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 1rem; max-width: 500px; margin-left: auto; margin-right: auto;">
        Votre demande d'adoption a bien été enregistrée et est <strong>en attente de validation</strong> par notre équipe.
    </p>
    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 3rem;">
        Vous serez contacté dès qu'un administrateur aura traité votre demande.
    </p>
    
    <!-- Lien permettant au client de retourner à la page d'accueil -->
    <a href="index.php">
        <button style="width: auto; padding: 1rem 2rem; border-radius: 99px;">Retour à la boutique</button>
    </a>
</div>

<?php
include 'includes/footer.php';
?>