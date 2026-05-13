    </div> <!-- End container -->
    <footer style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.8rem; margin-top: 5rem; border-top: 1px solid var(--glass-border);">
        &copy; <?= date('Y') ?> Cartoon's Animals. Tous droits réservés.
    </footer>

    <!-- Script pour désactiver la recharge de page lors de l'ajout au panier -->
    <script>
    document.addEventListener('submit', function(event) {
        // On cible uniquement les formulaires qui pointent vers add_to_cart.php
        if (event.target.action && event.target.action.includes('add_to_cart.php')) {
            event.preventDefault(); // Annule l'envoi classique du formulaire (rechargement)

            const form = event.target;
            const formData = new FormData(form);
            formData.append('ajax', '1'); // Flag pour le serveur

            // Sélection du bouton pour feedback visuel
            const btn = form.querySelector('button');
            const originalText = btn.innerText;
            
            // État de chargement
            btn.innerText = '⏳...';
            btn.disabled = true;

            // Envoi de la requête en arrière-plan
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mise à jour dynamique du badge du panier sans recharger
                    const badge = document.querySelector('.badge');
                    if (badge) {
                        badge.innerText = data.total_items;
                        // On déclenche l'animation de pulsation
                        badge.classList.remove('pulse');
                        void badge.offsetWidth; // Force le reflow pour redéclencher l'animation
                        badge.classList.add('pulse');
                    }
                    
                    // On récupère l'ID du produit ajouté
                    const productId = formData.get('product_id');

                    // On cherche tous les formulaires d'ajout au panier pour cet animal (carte + modal)
                    const allForms = document.querySelectorAll(`form[action*="add_to_cart.php"] input[name="product_id"][value="${productId}"]`);
                    
                    allForms.forEach(input => {
                        const parentForm = input.closest('form');
                        
                        // On remplace le formulaire par un bouton désactivé
                        const disabledBtn = document.createElement('button');
                        disabledBtn.type = 'button';
                        disabledBtn.className = 'btn-outline btn-disabled';
                        disabledBtn.innerText = 'Déjà dans le panier';
                        disabledBtn.disabled = true;
                        disabledBtn.style.width = '100%';
                        disabledBtn.onclick = (e) => e.stopPropagation();
                        
                        // Si on est dans les favoris ou le modal, on garde le flex: 1
                        if (parentForm.style.flex === '1' || (parentForm.parentElement && parentForm.parentElement.style.display === 'flex')) {
                            disabledBtn.style.flex = '1';
                        }

                        parentForm.parentNode.replaceChild(disabledBtn, parentForm);
                    });

                } else if (data.error === 'Non connecté') {
                    // Si la session a expiré, on redirige vers la connexion
                    window.location.href = 'login.php';
                }
            })
            .catch(error => {
                console.error('Erreur AJAX:', error);
                btn.innerText = '❌ Erreur';
                btn.disabled = false;
                setTimeout(() => btn.innerText = originalText, 2000);
            });
        }
    });
    </script>
</body>
</html>
