<!-- Squelette de la pop-up (Modal) -->
<div id="animalModal" class="modal">
    <div class="modal-content">
        <!-- Bouton pour fermer la pop-up -->
        <span class="close-modal">&times;</span>
        
        <!-- Le contenu sera chargé ici dynamiquement via JavaScript -->
        <div id="modalBodyContent">
            <p style="padding: 20px;">Chargement des informations...</p>
        </div>
    </div>
</div>

<script>
/**
 * Script pour gérer l'ouverture et la fermeture de la pop-up
 */
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('animalModal');
    const closeBtn = document.querySelector('.close-modal');
    const modalBody = document.getElementById('modalBodyContent');

    // Fonction pour ouvrir la pop-up et charger les données
    window.openAnimalDetails = function(animalId) {
        modal.style.display = 'block';
        modalBody.innerHTML = '<p style="padding: 20px;">Chargement des informations...</p>';

        // Appel AJAX vers le fichier PHP séparé
        fetch('animal_details.php?id=' + animalId)
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                modalBody.innerHTML = '<p style="padding: 20px;">Une erreur est survenue lors du chargement.</p>';
            });
    };

    // Fermer la pop-up quand on clique sur la croix
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }

    // Fermer la pop-up quand on clique en dehors de la fenêtre
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>
