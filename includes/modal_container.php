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
    
    // Fonction AJAX pour ajouter/retirer des favoris sans recharger la page
    window.toggleFavorite = function(productId) {
        // Envoi de la requête
        fetch('add_favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'product_id=' + productId + '&ajax=1'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Mise à jour du bouton dans la modale
                const btn = document.getElementById('btn-fav-' + productId);
                const svg = document.getElementById('svg-fav-' + productId);
                
                if (data.is_favorite) {
                    btn.style.backgroundColor = '#ffe6e6';
                    btn.style.color = 'red';
                    svg.setAttribute('fill', 'red');
                    svg.setAttribute('stroke', 'red');
                } else {
                    btn.style.backgroundColor = 'white';
                    btn.style.color = 'var(--text-main)';
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke', 'currentColor');
                }
                
                // Mise à jour du petit coeur indicateur sur la page principale (s'il existe)
                const indicator = document.getElementById('fav-indicator-' + productId);
                if (indicator) {
                    indicator.style.display = data.is_favorite ? 'block' : 'none';
                }
                
                // Si on est sur la page favorites.php, on peut vouloir rafraîchir ou masquer la carte
                // Optionnel: document.getElementById('card-' + productId).style.display = 'none';
            } else {
                alert(data.error || "Une erreur est survenue.");
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
        });
    };
});
</script>
