# 🎮 GameVault - Site E-commerce Premium

Ce projet est une plateforme de vente de jeux vidéo développée en PHP avec une base de données SQLite. 

## ✨ Fonctionnalités
- **Boutique** : Liste des jeux disponibles avec descriptions et prix.
- **Panier** : Gestion dynamique du panier (ajout, suppression, calcul du total).
- **Authentification** : Système d'inscription et de connexion sécurisé (hachage de mot de passe).
- **Administration** : Espace dédié pour gérer les produits et les utilisateurs.
- **Design Moderne** : Interface responsive avec Glassmorphism et Dark Mode.

## 🚀 Installation et Lancement

### Prérequis
- [PHP](https://www.php.net/downloads.php) (Version 7.4 ou supérieure conseillée)
- Extension PHP SQLite (généralement incluse par défaut)

### Lancer le projet
1. Ouvrez un terminal dans le dossier `site_ecommerce`.
2. Lancez le serveur intégré de PHP avec la commande suivante :
   ```bash
   php -S localhost:8000
   ```
3. Ouvrez votre navigateur et allez sur : [http://localhost:8000](http://localhost:8000)

## 👤 Accès Admin
Un compte administrateur est déjà disponible pour tester les fonctionnalités de gestion :
- **Utilisateur** : `kawete`
- **Mot de passe** : *(Veuillez réinitialiser ou créer un nouveau compte si vous avez perdu le mot de passe original)*

## 🛠️ Structure du Projet
- `index.php` : Page d'accueil / Boutique
- `cart.php` : Gestion du panier
- `admin.php` : Tableau de bord administrateur
- `assets/css/style.css` : Design système (Vanilla CSS)
- `includes/` : Composants réutilisables (Header/Footer)
- `database.sqlite` : Base de données locale
