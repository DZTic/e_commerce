-- Ce script ajoute une colonne 'image' à la table 'products' et met à jour les produits avec les chemins de leurs images respectives.

-- 1. Ajout de la colonne 'image' à la table 'products'
-- S'il y a déjà une colonne image, cette commande retournera une erreur, c'est normal si on l'exécute plusieurs fois.
ALTER TABLE products ADD COLUMN image TEXT;

-- 2. Mise à jour des images pour chaque animal dans la base de données
-- On utilise la commande UPDATE pour modifier l'enregistrement correspondant à chaque ID.
UPDATE products SET image = 'assets/images/golden_retriever.png' WHERE name = 'Golden Retriever';
UPDATE products SET image = 'assets/images/chat_siamois.png' WHERE name = 'Chat Siamois';
UPDATE products SET image = 'assets/images/lapin_nain.png' WHERE name = 'Lapin Nain';
UPDATE products SET image = 'assets/images/perroquet_gris.png' WHERE name = 'Perroquet Gris du Gabon';
UPDATE products SET image = 'assets/images/poisson_combattant.png' WHERE name = 'Poisson Combattant';
UPDATE products SET image = 'assets/images/chat_mechant.png' WHERE name = 'chat méchant';
