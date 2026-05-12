# 🎮 DOCUMENTATION COMPLÈTE - Cartoon's Animals
## Site E-commerce PHP/SQLite

---

## 📋 TABLE DES MATIÈRES

1. [Vue d'ensemble du projet](#vue-densemble)
2. [Architecture technique](#architecture)
3. [Structure des fichiers](#structure)
4. [Base de données](#base-de-données)
5. [Fonctionnalités détaillées](#fonctionnalités)
6. [Explication ligne par ligne](#ligne-par-ligne)
7. [Sécurité](#sécurité)
8. [Design et UX](#design)
9. [Points forts et améliorations](#points-forts)

---

## 🎯 VUE D'ENSEMBLE

### Qu'est-ce que Cartoon's Animals ?

**Cartoon's Animals** est une plateforme e-commerce développée en PHP avec une base de données SQLite, permettant l'adoption d'animaux de compagnie. Le site se distingue par son design cartoonish avec des bordures noires épaisses, des ombres dures et des couleurs vives rappelant les bandes dessinées.

### Objectifs principaux

- ✅ Permettre aux utilisateurs de parcourir une liste d'animaux disponibles
- ✅ Gérer un panier d'achat dynamique
- ✅ Système d'authentification sécurisé (inscription/connexion)
- ✅ Espace d'administration pour gérer produits et utilisateurs
- ✅ Système de favoris pour sauvegarder les animaux préférés
- ✅ Possibilité pour tout utilisateur de proposer un animal à l'adoption
- ✅ Interface responsive et moderne avec style cartoon

### Technologies utilisées

- **Backend** : PHP 7.4+
- **Base de données** : SQLite (fichier db.sqlite)
- **Frontend** : HTML5, CSS3, JavaScript (vanilla)
- **Design** : Style cartoon avec Glassmorphism et Dark Mode
- **Upload d'images** : Gestion native PHP

---

## 🏗️ ARCHITECTURE TECHNIQUE

### Pattern architectural

Le projet suit une architecture **MVC (Model-View-Controller) simplifiée** :

- **Model** : Requêtes SQL directes dans les fichiers PHP
- **View** : Fichiers PHP avec HTML mélangé
- **Controller** : Logique de traitement dans les fichiers PHP

### Flux de données typique

```
Utilisateur → Formulaire HTML → Requête POST → Traitement PHP → Base SQLite → Réponse
```

### Gestion des sessions

- `session_start()` au début de chaque page
- `$_SESSION['user_id']` stocke l'ID utilisateur connecté
- Vérification systématique de l'authentification

### Communication AJAX

Plusieurs fonctionnalités utilisent **AJAX** pour éviter le rechargement de page :
- Ajout au panier (`add_to_cart.php`)
- Gestion des favoris (`add_favorite.php`)
- Chargement des détails d'animal (`animal_details.php`)

---

## 📁 STRUCTURE DES FICHIERS

### Arborescence complète

```
e_commerce-main/
├── config.php                    # Configuration BDD
├── index.php                     # Page d'accueil / Boutique
├── login.php                     # Page de connexion
├── register.php                  # Page d'inscription
├── logout.php                    # Déconnexion
├── cart.php                      # Gestion du panier
├── favorites.php                  # Liste des favoris
├── admin.php                     # Dashboard admin
├── admin_products.php             # Gestion des produits (admin)
├── admin_users.php               # Gestion des utilisateurs (admin)
├── add_products.php              # Traitement ajout produit
├── add_to_cart.php               # Traitement ajout panier
├── add_favorite.php              # Traitement favoris
├── remove_from_cart.php          # Suppression panier
├── delete_products.php           # Suppression produit
├── delete_users.php              # Suppression utilisateur
├── validate_order.php            # Validation commande
├── confirmation.php              # Page confirmation
├── propose_animal.php            # Proposition animal
├── animal_details.php            # Détails animal (AJAX)
├── nav.php                       # Barre de recherche
├── db.sqlite                     # Base de données SQLite
├── assets/
│   ├── css/
│   │   └── style.css            # Feuille de style principale
│   └── images/                  # Images des animaux uploadées
├── includes/
│   ├── header.php               # En-tête HTML + navigation
│   ├── footer.php               # Pied de page + scripts JS
│   ├── filters.php              # Filtres catégories
│   └── modal_container.php     # Pop-up détails animal
└── README.md                    # Documentation du projet
```

### Rôle de chaque fichier

#### Fichiers de configuration

- **config.php** (5 lignes) : Connexion à la base SQLite avec PDO

#### Fichiers principaux

- **index.php** (129 lignes) : Page d'accueil affichant la grille de produits avec filtres
- **login.php** (47 lignes) : Formulaire de connexion avec vérification mot de passe
- **register.php** (70 lignes) : Formulaire d'inscription avec hachage mot de passe
- **cart.php** (104 lignes) : Affichage du panier avec tableau des articles
- **favorites.php** (101 lignes) : Liste des animaux favoris de l'utilisateur

#### Fichiers d'administration

- **admin.php** (42 lignes) : Dashboard admin avec liens vers gestion produits/utilisateurs
- **admin_products.php** (94 lignes) : Interface CRUD pour les produits
- **admin_users.php** (54 lignes) : Liste des utilisateurs avec suppression

#### Fichiers de traitement

- **add_products.php** (80 lignes) : Traitement formulaire ajout produit + upload image
- **add_to_cart.php** (58 lignes) : Ajout produit au panier (AJAX ou classique)
- **add_favorite.php** (63 lignes) : Toggle favoris (AJAX)
- **remove_from_cart.php** (26 lignes) : Suppression articles du panier
- **validate_order.php** (45 lignes) : Validation commande + marquage vendus
- **delete_products.php** (17 lignes) : Suppression produit par admin
- **delete_users.php** (17 lignes) : Suppression utilisateur par admin

#### Fichiers utilitaires

- **logout.php** (5 lignes) : Destruction de session
- **confirmation.php** (34 lignes) : Page de confirmation après commande
- **propose_animal.php** (158 lignes) : Formulaire proposition animal par utilisateur
- **animal_details.php** (90 lignes) : Génération HTML détails pour pop-up
- **nav.php** (22 lignes) : Barre de recherche globale

#### Fichiers includes

- **includes/header.php** (49 lignes) : Navigation + badge panier
- **includes/footer.php** (72 lignes) : Pied de page + script AJAX panier
- **includes/filters.php** (24 lignes) : Filtres catégories/sous-catégories
- **includes/modal_container.php** (99 lignes) : Structure pop-up + scripts JS

#### Fichiers assets

- **assets/css/style.css** (583 lignes) : Styles complets cartoon
- **assets/images/** : Dossier stockant les images uploadées

---

## 🗄️ BASE DE DONNÉES

### Schéma complet

```sql
-- Table des utilisateurs
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin INTEGER DEFAULT 0
);

-- Table des catégories
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

-- Table des sous-catégories
CREATE TABLE subcategories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER,
    name TEXT NOT NULL,
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

-- Table des produits (animaux)
CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    description TEXT,
    price REAL,
    image TEXT,
    age INTEGER,
    health TEXT,
    character TEXT,
    availability TEXT,
    subcategory_id INTEGER REFERENCES subcategories(id),
    is_sold INTEGER DEFAULT 0
);

-- Table des commandes
CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    total REAL,
    created_at TEXT
);

-- Table des articles du panier
CREATE TABLE cart_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    product_id INTEGER,
    quantity INTEGER DEFAULT 1,
    FOREIGN KEY(product_id) REFERENCES products(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

-- Table des favoris
CREATE TABLE favorites (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    product_id INTEGER,
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(product_id) REFERENCES products(id),
    UNIQUE(user_id, product_id)
);
```

### Relations entre tables

```
users (1) ----< (N) cart_items
users (1) ----< (N) favorites
users (1) ----< (N) orders

categories (1) ----< (N) subcategories
subcategories (1) ----< (N) products

products (1) ----< (N) cart_items
products (1) ----< (N) favorites
```

### Contraintes d'intégrité

- **UNIQUE** sur `users.username` : pas de doublons de noms d'utilisateur
- **UNIQUE** sur `favorites(user_id, product_id)` : un utilisateur ne peut mettre un produit en favori qu'une fois
- **FOREIGN KEY** : intégrité référentielle entre les tables
- **DEFAULT 0** sur `is_sold` et `is_admin` : valeurs par défaut sécurisées

---

## ⚙️ FONCTIONNALITÉS DÉTAILLÉES

### 1. Système d'authentification

#### Inscription (register.php)

**Processus :**
1. Vérification si le nom d'utilisateur existe déjà
2. Hachage du mot de passe avec `password_hash()`
3. Insertion en base avec `is_admin = 0` par défaut
4. Redirection vers la page de connexion

**Sécurité :**
- Hachage bcrypt avec `PASSWORD_DEFAULT`
- Protection contre les doublons de username
- Validation des champs obligatoires

#### Connexion (login.php)

**Processus :**
1. Récupération de l'utilisateur par username
2. Vérification du mot de passe avec `password_verify()`
3. Création de session `$_SESSION['user_id']`
4. Redirection vers l'accueil

**Sécurité :**
- Vérification sécurisée du mot de passe haché
- Message d'erreur générique en cas d'échec

#### Déconnexion (logout.php)

**Processus :**
1. Destruction de toutes les variables de session
2. Redirection vers la page de connexion

### 2. Gestion du panier

#### Ajout au panier (add_to_cart.php)

**Fonctionnalités :**
- Détection AJAX via header `X-Requested-With`
- Vérification si le produit est déjà dans le panier
- Vérification si le produit n'est pas déjà vendu
- Quantité fixée à 1 (animal unique)
- Mise à jour du badge panier en temps réel

**Code clé :**
```php
// Vérification doublon
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);

if ($stmt->fetch()) {
    // Déjà dans le panier, rien à faire
} else {
    // Ajout avec quantité = 1
    $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)")
        ->execute([$user_id, $product_id]);
}
```

#### Affichage du panier (cart.php)

**Fonctionnalités :**
- Jointure entre `cart_items` et `products`
- Calcul automatique du total
- Boutons de suppression individuels
- Bouton "Vider le panier"
- Bouton "Valider la commande"

**Requête SQL principale :**
```sql
SELECT p.id, p.name, p.price, ci.quantity
FROM cart_items ci
JOIN products p ON ci.product_id = p.id
WHERE ci.user_id = ?
```

#### Suppression du panier (remove_from_cart.php)

**Actions possibles :**
- `remove` : Supprimer un article spécifique
- `clear` : Vider tout le panier

### 3. Système de favoris

#### Toggle favoris (add_favorite.php)

**Fonctionnalités :**
- Détection AJAX
- Vérification si déjà en favori
- Ajout OU suppression selon l'état actuel
- Mise à jour visuelle immédiate

**Logique toggle :**
```php
// Vérification état actuel
$stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$favorite = $stmt->fetch();

if ($favorite) {
    // Suppression
    $stmtDelete = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmtDelete->execute([$user_id, $product_id]);
} else {
    // Ajout
    $stmtInsert = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmtInsert->execute([$user_id, $product_id]);
}
```

#### Affichage des favoris (favorites.php)

**Fonctionnalités :**
- Jointure triple : `products` + `favorites` + `categories` + `subcategories`
- Affichage identique à la boutique
- Boutons d'action (panier + retirer favoris)

### 4. Gestion des produits (Admin)

#### Ajout de produit (add_products.php)

**Processus complet :**
1. Vérification droits admin
2. Validation et upload de l'image
3. Validation des données (prix ≥ 0, âge ≥ 0)
4. Insertion en base de données
5. Redirection vers l'interface admin

**Upload d'image :**
```php
if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
    $nom = $_FILES['fichier']['name'];
    $tmp = $_FILES['fichier']['tmp_name'];
    
    // Validation extension
    $extension = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
    $extensionsAutorisees = ['png', 'jpg', 'jpeg'];
    
    if (in_array($extension, $extensionsAutorisees)) {
        $destinationChemin = __DIR__ . '/assets/images/' . basename($nom);
        if (move_uploaded_file($tmp, $destinationChemin)) {
            $imagePath = 'assets/images/' . basename($nom);
        }
    }
}
```

**Validation des données :**
```php
// Vérification champs obligatoires
if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || 
    empty($_POST['subcategory_id']) || empty($_POST['age']) || empty($_POST['availability']) || 
    empty($_POST['health']) || empty($_POST['character'])) {
    header('Location: admin_products.php?error=champs_manquants');
    exit();
}

// Interdiction prix négatif
elseif ($_POST['price'] < 0) {
    header('Location: admin_products.php?error=prix_negatif');
    exit();
}

// Interdiction âge négatif
elseif ($_POST['age'] < 0) {
    header('Location: admin_products.php?error=age_negatif');
    exit();
}
```

#### Suppression de produit (delete_products.php)

**Sécurité :**
- Vérification droits admin
- Suppression par ID via GET
- Redirection automatique

### 5. Proposition d'animal (utilisateur)

#### Formulaire (propose_animal.php)

**Fonctionnalités :**
- Accessible à tout utilisateur connecté
- Formulaire complet avec tous les champs
- Upload d'image obligatoire
- Validation identique à l'admin
- Message de confirmation/erreur

**Différence avec admin :**
- Même logique que `add_products.php`
- Mais accessible aux utilisateurs non-admin
- Permet la contribution communautaire

### 6. Validation de commande

#### Processus (validate_order.php)

**Étapes :**
1. Calcul du total du panier
2. Création de l'enregistrement de commande
3. Récupération des produits achetés
4. Marquage des produits comme `is_sold = 1`
5. Suppression des produits de TOUS les paniers
6. Vidage du panier de l'utilisateur
7. Redirection vers confirmation

**Code clé :**
```php
// Marquage comme vendus
$placeholders = str_repeat('?,', count($purchased_products) - 1) . '?';
$update_stmt = $pdo->prepare("UPDATE products SET is_sold = 1 WHERE id IN ($placeholders)");
$update_stmt->execute($purchased_products);

// Retrait de tous les paniers
$delete_cart_stmt = $pdo->prepare("DELETE FROM cart_items WHERE product_id IN ($placeholders)");
$delete_cart_stmt->execute($purchased_products);
```

### 7. Filtres et recherche

#### Filtres par catégorie (index.php)

**Logique :**
- Récupération `category_id` et `subcategory_id` depuis GET
- Construction dynamique de la requête SQL
- Affichage des filtres actifs

**Requête dynamique :**
```php
$query = "SELECT p.*, s.name as subcategory_name, c.name as category_name 
          FROM products p 
          LEFT JOIN subcategories s ON p.subcategory_id = s.id 
          LEFT JOIN categories c ON s.category_id = c.id";

$conditions = [];
$params = [];

// Filtre recherche
if ($search) {
    $conditions[] = "p.name LIKE ?";
    $params[] = "%$search%";
}

// Filtre sous-catégorie ou catégorie
if ($subcategory_id) {
    $conditions[] = "p.subcategory_id = ?";
    $params[] = $subcategory_id;
} elseif ($category_id) {
    $conditions[] = "s.category_id = ?";
    $params[] = $category_id;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}
```

#### Recherche textuelle (nav.php)

**Fonctionnalités :**
- Recherche par nom uniquement
- Champ de saisie avec placeholder
- Bouton d'annulation si recherche active

### 8. Pop-up de détails (Modal)

#### Structure (modal_container.php)

**Composants :**
- Conteneur modal avec fond semi-transparent
- Bouton de fermeture (croix)
- Zone de contenu dynamique
- Scripts JavaScript pour ouverture/fermeture

#### Chargement dynamique (animal_details.php)

**Processus :**
1. Récupération ID animal depuis GET
2. Requête SQL avec jointures
3. Génération HTML complet
4. Retour du HTML pour injection dans modal

**Requête SQL :**
```sql
SELECT p.*, s.name as subcategory_name, c.name as category_name 
FROM products p 
LEFT JOIN subcategories s ON p.subcategory_id = s.id 
LEFT JOIN categories c ON s.category_id = c.id 
WHERE p.id = ?
```

#### JavaScript (modal_container.php)

**Fonctions :**
- `openAnimalDetails(animalId)` : Ouverture modal + chargement AJAX
- `toggleFavorite(productId)` : Gestion favoris sans rechargement
- Fermeture au clic sur croix ou hors modal

---

## 📝 EXPLICATION LIGNE PAR LIGNE

### config.php

```php
<?php
// Connexion à la base SQLite
$pdo = new PDO('sqlite:db.sqlite');
// Activation du mode d'erreur pour les exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
```

**Ligne 3** : Création d'objet PDO pour connexion SQLite
**Ligne 5** : Configuration PDO pour lancer des exceptions en cas d'erreur SQL

---

### index.php

#### Partie PHP (lignes 1-68)

```php
<?php
session_start();                    // Ligne 2 : Démarrage session
require 'config.php';               // Ligne 3 : Inclusion configuration BDD

if (!isset($_SESSION['user_id'])) { // Ligne 5 : Vérification authentification
    header('Location: login.php');  // Ligne 6 : Redirection si non connecté
    exit();                         // Ligne 7 : Arrêt script
}

// Récupération des filtres
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;     // Ligne 11
$subcategory_id = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : null; // Ligne 12
$search = isset($_GET['search']) ? $_GET['search'] : '';                      // Ligne 13

// Construction de la requête avec filtres et recherche
$query = "SELECT p.*, s.name as subcategory_name, c.name as category_name    // Ligne 16
          FROM products p                                                     // Ligne 17
          LEFT JOIN subcategories s ON p.subcategory_id = s.id               // Ligne 18
          LEFT JOIN categories c ON s.category_id = c.id";                  // Ligne 19

$conditions = [];                     // Ligne 21 : Tableau conditions WHERE
$params = [];                          // Ligne 22 : Tableau paramètres préparés

// Filtre par recherche textuelle (uniquement sur le nom)
if ($search) {                         // Ligne 25
    $conditions[] = "p.name LIKE ?";  // Ligne 26
    $params[] = "%$search%";          // Ligne 27 : Pattern LIKE avec wildcards
}

// Filtre par sous-catégorie ou catégorie
if ($subcategory_id) {                // Ligne 31
    $conditions[] = "p.subcategory_id = ?"; // Ligne 32
    $params[] = $subcategory_id;      // Ligne 33
} elseif ($category_id) {             // Ligne 34
    $conditions[] = "s.category_id = ?";   // Ligne 35
    $params[] = $category_id;         // Ligne 36
}

if (!empty($conditions)) {            // Ligne 39
    $query .= " WHERE " . implode(" AND ", $conditions); // Ligne 40 : Concaténation WHERE
}

$stmt = $pdo->prepare($query);        // Ligne 43 : Préparation requête
$stmt->execute($params);              // Ligne 44 : Exécution avec paramètres
$products = $stmt->fetchAll();        // Ligne 45 : Récupération tous résultats

// Récupération des favoris et du panier de l'utilisateur connecté
$user_favorites = [];                 // Ligne 48
$user_cart = [];                      // Ligne 49
if (isset($_SESSION['user_id'])) {   // Ligne 50
    $fav_stmt = $pdo->prepare("SELECT product_id FROM favorites WHERE user_id = ?"); // Ligne 51
    $fav_stmt->execute([$_SESSION['user_id']]); // Ligne 52
    $user_favorites = $fav_stmt->fetchAll(PDO::FETCH_COLUMN); // Ligne 53 : Tableau simple d'IDs

    $cart_stmt = $pdo->prepare("SELECT product_id FROM cart_items WHERE user_id = ?"); // Ligne 55
    $cart_stmt->execute([$_SESSION['user_id']]); // Ligne 56
    $user_cart = $cart_stmt->fetchAll(PDO::FETCH_COLUMN); // Ligne 57
}

// Récupération des catégories pour le filtre
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(); // Ligne 61
$subcategories = [];                  // Ligne 62
if ($category_id) {                   // Ligne 63
    $stmt = $pdo->prepare("SELECT * FROM subcategories WHERE category_id = ?"); // Ligne 64
    $stmt->execute([$category_id]);   // Ligne 65
    $subcategories = $stmt->fetchAll(); // Ligne 66
}

include 'includes/header.php';        // Ligne 69 : Inclusion en-tête
?>
```

#### Partie HTML (lignes 72-129)

```php
<header class="page-header">          // Ligne 72 : En-tête de page
    <h1>Découvrez nos animaux</h1>     // Ligne 73 : Titre principal
    <p>Trouvez le compagnon parfait pour votre famille.</p> // Ligne 74 : Sous-titre
</header>

<?php 
// Inclusion de la barre de recherche créée précédemment
include 'nav.php';                    // Ligne 79 : Barre de recherche
?>

<?php include 'includes/filters.php'; ?> // Ligne 82 : Filtres catégories

<div class="product-grid">            // Ligne 84 : Grille de produits
    <?php foreach($products as $row) { // Ligne 85 : Boucle sur produits
        $is_favorite = in_array($row['id'], $user_favorites); // Ligne 86 : Vérification favori
        $is_in_cart = in_array($row['id'], $user_cart);        // Ligne 87 : Vérification panier
    ?>
        <!-- On ajoute un curseur pointeur et un événement au clic pour ouvrir la pop-up -->
        <div class="card" onclick="openAnimalDetails(<?= $row['id'] ?>)" style="cursor: pointer; position: relative;"> // Ligne 90
            
            <!-- Petit cœur indicateur de favori sur l'image -->
            <div id="fav-indicator-<?= $row['id'] ?>" style="position: absolute; top: 15px; right: 15px; font-size: 1.5rem; display: <?= $is_favorite ? 'block' : 'none' ?>; pointer-events: none; text-shadow: 0px 0px 5px rgba(255, 255, 255, 0.8);"> // Ligne 93
                ❤️
            </div>
            <!-- Vérification et affichage de l'image de l'animal depuis la base de données -->
            <?php if (!empty($row['image'])): ?> // Ligne 97
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="card-img" style="width: 100%; border-top-left-radius: 8px; border-top-right-radius: 8px; height: 200px; object-fit: cover;"> // Ligne 98
            <?php endif; ?>
            <div class="card-content"> // Ligne 100
                <div style="font-size: 0.8rem; color: var(--primary); font-weight: bold; text-transform: uppercase; margin-bottom: 5px;"> // Ligne 101
                    <?= htmlspecialchars($row['category_name'] ?? 'Divers') ?> / <?= htmlspecialchars($row['subcategory_name'] ?? 'Inconnu') ?> // Ligne 102
                </div>
                <h3><?= htmlspecialchars($row['name']) ?></h3> // Ligne 104
                <p><?= htmlspecialchars($row['description']) ?></p> // Ligne 105
            </div>
            <div class="price"><?= number_format($row['price'], 2) ?> EUR</div> // Ligne 107
            
            <!-- Affichage du bouton selon l'état de l'animal -->
            <?php if ($row['is_sold']): ?> // Ligne 110
                <button type="button" class="btn-outline" style="background-color: #ffe6e6; border-color: #ffcccc; color: #cc0000; cursor: not-allowed; width: 100%;" onclick="event.stopPropagation();" disabled>Déjà adopté</button> // Ligne 111
            <?php elseif ($is_in_cart): ?> // Ligne 112
                <button type="button" class="btn-outline" style="background-color: #f0f0f0; border-color: #ccc; color: #888; cursor: not-allowed; width: 100%;" onclick="event.stopPropagation();" disabled>Déjà dans le panier</button> // Ligne 113
            <?php else: ?> // Ligne 114
                <form method="post" action="add_to_cart.php" onclick="event.stopPropagation();"> // Ligne 115
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>"> // Ligne 116
                    <button type="submit" class="btn-outline">Ajouter au panier</button> // Ligne 117
                </form>
            <?php endif; ?> // Ligne 118
        </div>
    <?php } ?> // Ligne 120
</div>

<?php 
// Inclusion de la structure de la pop-up et de sa logique JavaScript
include 'includes/modal_container.php'; // Ligne 126
?>

<?php include 'includes/footer.php'; ?> // Ligne 128
```

---

### login.php

```php
<?php
session_start();                    // Ligne 2 : Démarrage session
require 'config.php';               // Ligne 3 : Configuration BDD

if (isset($_SESSION['user_id'])) {  // Ligne 5 : Déjà connecté ?
    header('Location: index.php');  // Ligne 6 : Redirection accueil
    exit();                         // Ligne 7 : Arrêt script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Ligne 10 : Formulaire soumis ?
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?"); // Ligne 11
    $stmt->execute([$_POST['username']]); // Ligne 12
    $user = $stmt->fetch();          // Ligne 13 : Récupération utilisateur

    if ($user && password_verify($_POST['password'], $user['password'])) { // Ligne 15 : Vérification mot de passe
        $_SESSION['user_id'] = $user['id']; // Ligne 16 : Création session
        header("Location: index.php"); // Ligne 17 : Redirection
        exit();                      // Ligne 18 : Arrêt
    } else {                         // Ligne 19
        $error = "Identifiants incorrects"; // Ligne 20 : Message erreur
    }
}

include 'includes/header.php';      // Ligne 24 : Inclusion en-tête
?>
```

**Ligne 15** : `password_verify()` compare le mot de passe en clair avec le hash stocké
**Ligne 16** : Stockage de l'ID utilisateur en session pour les pages suivantes

---

### register.php

```php
<?php
/**
 * register.php
 * Gère l'inscription des nouveaux utilisateurs.
 */
session_start();                    // Ligne 6 : Démarrage session
require 'config.php';               // Ligne 7 : Configuration BDD

$error = "";                        // Ligne 9 : Variable erreur

// Traitement du formulaire lorsqu'il est soumis via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Ligne 12
    $username = $_POST['username']; // Ligne 13
    $password = $_POST['password']; // Ligne 14

    // 1. Vérification de la disponibilité du nom d'utilisateur
    // On compte combien d'utilisateurs possèdent déjà ce pseudo
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?"); // Ligne 18
    $stmt->execute([$username]);    // Ligne 19
    $userExists = $stmt->fetchColumn() > 0; // Ligne 20 : Vérification si > 0

    if ($userExists) {              // Ligne 22
        // Si le pseudo est pris, on prépare un message d'erreur
        $error = "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre."; // Ligne 24
    } else {                         // Ligne 25
        // 2. Création du compte si le pseudo est libre
        // On hache le mot de passe pour la sécurité avant de l'enregistrer
        $hash = password_hash($password, PASSWORD_DEFAULT); // Ligne 28 : Hachage bcrypt

        // Insertion du nouvel utilisateur (is_admin est à 0 par défaut)
        $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)"); // Ligne 31
        $stmt->execute([$username, $hash]); // Ligne 32

        // Redirection vers la page de connexion après succès
        header("Location: login.php"); // Ligne 35
        exit();                      // Ligne 36
    }
}

// Inclusion de l'en-tête du site
include 'includes/header.php';      // Ligne 41
?>
```

**Ligne 28** : `password_hash()` avec `PASSWORD_DEFAULT` utilise bcrypt automatiquement
**Ligne 31** : `is_admin = 0` par défaut, seul le premier utilisateur peut être admin manuellement

---

### add_to_cart.php

```php
<?php
session_start();                    // Ligne 2
require 'config.php';               // Ligne 3

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) { // Ligne 6
    // Si ce n'est pas une requête AJAX, on redirige
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { // Ligne 8
        echo json_encode(['error' => 'Non connecté']); // Ligne 9 : Réponse JSON erreur
        exit();                     // Ligne 10
    }
    header('Location: login.php');  // Ligne 12 : Redirection classique
    exit();                         // Ligne 13
}

$user_id = $_SESSION['user_id'];    // Ligne 16
$product_id = $_POST['product_id']; // Ligne 17

// Vérifie si le produit est déjà dans le panier
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?"); // Ligne 20
$stmt->execute([$user_id, $product_id]); // Ligne 21

if ($stmt->fetch()) {               // Ligne 23 : Déjà dans panier
    // Un animal est unique, on ne peut l'acheter qu'une seule fois.
    // Donc si l'article existe déjà dans le panier, on ne l'incrémente pas.
} else {                            // Ligne 26
    // Vérifie si le produit est déjà vendu (par mesure de sécurité) avant de l'ajouter
    $stmt_sold = $pdo->prepare("SELECT is_sold FROM products WHERE id = ?"); // Ligne 28
    $stmt_sold->execute([$product_id]); // Ligne 29
    $is_sold = $stmt_sold->fetchColumn(); // Ligne 30

    if (!$is_sold) {                 // Ligne 32 : Pas encore vendu
        // Sinon, ajoute l'article avec une quantité fixe de 1
        $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)") // Ligne 34
            ->execute([$user_id, $product_id]); // Ligne 35
    }
}

// Calcul du nouveau total d'articles pour la réponse AJAX
$stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?"); // Ligne 40
$stmt->execute([$user_id]);         // Ligne 41
$total_items = $stmt->fetchColumn() ?: 0; // Ligne 42 : 0 si NULL

// Si c'est une requête AJAX (détectée via l'en-tête X-Requested-With ou si on décide de tout passer en JSON)
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || isset($_POST['ajax'])) { // Ligne 45
    header('Content-Type: application/json'); // Ligne 46 : Header JSON
    echo json_encode([              // Ligne 47
        'success' => true,          // Ligne 48
        'total_items' => $total_items, // Ligne 49
        'message' => 'Produit ajouté au panier !' // Ligne 50
    ]);
    exit();                         // Ligne 52
}

// Comportement par défaut (fallback si JS est désactivé)
header('Location: index.php');      // Ligne 56
exit();                             // Ligne 57
```

**Ligne 8** : Détection AJAX via header `X-Requested-With`
**Ligne 34** : Quantité fixée à 1 car chaque animal est unique
**Ligne 42** : `?: 0` opérateur ternaire pour gérer NULL

---

### validate_order.php

```php
<?php
session_start();                    // Ligne 2
require 'config.php';               // Ligne 3

$user_id = $_SESSION['user_id'];    // Ligne 5

// Calculer le total du panier
$stmt = $pdo->prepare("             // Ligne 8
    SELECT SUM(ci.quantity * p.price) as total_price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");                                 // Ligne 13
$stmt->execute([$user_id]);         // Ligne 14
$total_price = $stmt->fetchColumn(); // Ligne 15
$total_price = $total_price ?: 0;   // Ligne 16 : Gestion NULL

// Insérer la commande dans la table orders
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total, created_at) VALUES (?, ?, datetime('now'))"); // Ligne 19
$stmt->execute([$user_id, $total_price]); // Ligne 20

// Récupérer les produits du panier pour les marquer comme vendus
$stmt = $pdo->prepare("SELECT product_id FROM cart_items WHERE user_id = ?"); // Ligne 23
$stmt->execute([$user_id]);         // Ligne 24
$purchased_products = $stmt->fetchAll(PDO::FETCH_COLUMN); // Ligne 25 : Tableau simple d'IDs

if (!empty($purchased_products)) {  // Ligne 27
    // Créer une liste de "?" pour la requête préparée IN (...)
    $placeholders = str_repeat('?,', count($purchased_products) - 1) . '?'; // Ligne 29
    
    // Marquer les animaux comme vendus (is_sold = 1) car chaque animal est unique et ne peut être acheté qu'une fois
    $update_stmt = $pdo->prepare("UPDATE products SET is_sold = 1 WHERE id IN ($placeholders)"); // Ligne 32
    $update_stmt->execute($purchased_products); // Ligne 33

    // Retirer ces animaux des paniers de tous les utilisateurs (pour qu'ils ne puissent plus les acheter)
    $delete_cart_stmt = $pdo->prepare("DELETE FROM cart_items WHERE product_id IN ($placeholders)"); // Ligne 36
    $delete_cart_stmt->execute($purchased_products); // Ligne 37
}

// Par sécurité, vider le reste du panier de l'utilisateur
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?"); // Ligne 41
$stmt->execute([$user_id]);         // Ligne 42

header("Location: confirmation.php"); // Ligne 44
exit();                             // Ligne 45
```

**Ligne 29** : `str_repeat('?,', count($purchased_products) - 1) . '?'` génère "?,?,?,..." selon le nombre d'IDs
**Ligne 32** : Utilisation de `$placeholders` dans requête préparée pour clause IN

---

### add_products.php

```php
<?php
session_start();                    // Ligne 2
require 'config.php';               // Ligne 3

// Security Check
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); } // Ligne 6
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?"); // Ligne 7
$stmt->execute([$_SESSION['user_id']]); // Ligne 8
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); } // Ligne 9

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Ligne 11
    $imagePath = null;              // Ligne 12 : Par défaut, pas d'image

    // 1. Vérifier si un fichier a été envoyé dans le formulaire et s'il n'y a pas d'erreur lors de l'upload
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) { // Ligne 15
        $nom = $_FILES['fichier']['name']; // Ligne 16 : Nom original
        $tmp = $_FILES['fichier']['tmp_name']; // Ligne 17 : Chemin temporaire
        
        // --- NOUVEAU : Validation de l'extension ---
        // On récupère l'extension du fichier (ex: png)
        $extension = strtolower(pathinfo($nom, PATHINFO_EXTENSION)); // Ligne 21
        // Liste des extensions autorisées
        $extensionsAutorisees = ['png', 'jpg', 'jpeg']; // Ligne 23

        if (in_array($extension, $extensionsAutorisees)) { // Ligne 25
            // 2. Définir le chemin de destination. __DIR__ donne le dossier actuel
            $destinationChemin = __DIR__ . '/assets/images/' . basename($nom); // Ligne 27
            
            // 3. Déplacer le fichier du dossier temporaire vers son dossier final
            if (move_uploaded_file($tmp, $destinationChemin)) { // Ligne 30
                // 4. Si c'est un succès, on sauvegarde le chemin relatif qui sera mis dans la base de données
                $imagePath = 'assets/images/' . basename($nom); // Ligne 32
            }
        } else {                    // Ligne 34
            // Optionnel : on pourrait stocker une erreur ici si l'extension n'est pas bonne
        }
        // -------------------------------------------
    }

    // --- NOUVEAU : Validation des données ---
    // On vérifie que tous les champs obligatoires sont remplis
    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || 
        empty($_POST['subcategory_id']) || empty($_POST['age']) || empty($_POST['availability']) || 
        empty($_POST['health']) || empty($_POST['character'])) { // Ligne 42-44
        
        // En cas d'erreur, on redirige avec un message d'erreur
        header('Location: admin_products.php?error=champs_manquants'); // Ligne 48
        exit();                     // Ligne 49
    } 
    // Interdiction des prix négatifs
    elseif ($_POST['price'] < 0) {  // Ligne 52
        header('Location: admin_products.php?error=prix_negatif'); // Ligne 53
        exit();                     // Ligne 54
    }
    // Interdiction des âges négatifs
    elseif ($_POST['age'] < 0) {    // Ligne 57
        header('Location: admin_products.php?error=age_negatif'); // Ligne 58
        exit();                     // Ligne 59
    }

    // 5. Insérer le produit dans la base de données si tout est valide
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, age, health, character, availability, subcategory_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); // Ligne 63
    $stmt->execute([                // Ligne 64
        $_POST['name'],             // Ligne 65
        $_POST['description'],      // Ligne 66
        $_POST['price'],           // Ligne 67
        $imagePath,                 // Ligne 68
        $_POST['age'],             // Ligne 69
        $_POST['health'],           // Ligne 70
        $_POST['character'],        // Ligne 71
        $_POST['availability'],     // Ligne 72
        $_POST['subcategory_id']    // Ligne 73
    ]);
}

// 6. On redirige l'utilisateur vers la page d'administration
header('Location: admin_products.php'); // Ligne 78
exit();                             // Ligne 79
?>
```

**Ligne 21** : `pathinfo($nom, PATHINFO_EXTENSION)` extrait l'extension du fichier
**Ligne 27** : `__DIR__` donne le chemin absolu du dossier courant
**Ligne 30** : `move_uploaded_file()` déplace le fichier du temporaire au final

---

### includes/header.php

```php
<?php
if (session_status() === PHP_SESSION_NONE) { // Ligne 2 : Vérification session non démarrée
    session_start();                // Ligne 3 : Démarrage si nécessaire
}
require_once dirname(__DIR__) . '/config.php'; // Ligne 5 : Inclusion config (chemin absolu)

// Calcul du total du panier pour le badge
$cart_badges = 0;                  // Ligne 8
if (isset($_SESSION['user_id'])) { // Ligne 9
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?"); // Ligne 10
    $stmt->execute([$_SESSION['user_id']]); // Ligne 11
    $cart_badges = $stmt->fetchColumn() ?: 0; // Ligne 12 : 0 si NULL
}
?>
<!DOCTYPE html>                     // Ligne 15
<html lang="fr">                    // Ligne 16
<head>                             // Ligne 17
    <meta charset="UTF-8">          // Ligne 18
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> // Ligne 19
    <title>Cartoon's Animals - Boutique</title> // Ligne 20
    <link rel="preconnect" href="https://fonts.googleapis.com"> // Ligne 21
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> // Ligne 22
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"> // Ligne 23
    <link rel="stylesheet" href="assets/css/style.css"> // Ligne 24
</head>                            // Ligne 25
<body>                             // Ligne 26
    <nav>                          // Ligne 27
        <div class="logo">Cartoon's Animals</div> // Ligne 28
        <div>                      // Ligne 29
            <a href="index.php">Boutique</a> // Ligne 30
            <?php if (isset($_SESSION['user_id'])): ?> // Ligne 31
                <a href="propose_animal.php">Proposer un animal</a> // Ligne 32
                <a href="favorites.php">Mes Favoris</a> // Ligne 33
                <a href="cart.php">Panier <span class="badge"><?= $cart_badges ?></span></a> // Ligne 34
                <?php 
                // Check if admin
                $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?"); // Ligne 37
                $stmt->execute([$_SESSION['user_id']]); // Ligne 38
                if ($stmt->fetchColumn()): ?> // Ligne 39
                    <a href="admin.php">Admin</a> // Ligne 40
                <?php endif; ?> // Ligne 41
                <a href="logout.php">Déconnexion</a> // Ligne 42
            <?php else: ?> // Ligne 43
                <a href="login.php">Connexion</a> // Ligne 44
                <a href="register.php">Inscription</a> // Ligne 45
            <?php endif; ?> // Ligne 46
        </div>                      // Ligne 47
    </nav>                         // Ligne 48
    <div class="container">        // Ligne 49
```

**Ligne 2** : `session_status()` vérifie si une session est déjà active
**Ligne 5** : `dirname(__DIR__)` remonte d'un niveau puis inclut config.php
**Ligne 12** : `?: 0` gère le cas où le panier est vide (NULL)

---

### includes/footer.php

```php
    </div> <!-- End container -->    // Ligne 1
    <footer style="text-align: center; padding: 2rem; color: var(--text-muted); font-size: 0.8rem; margin-top: 5rem; border-top: 1px solid var(--glass-border);"> // Ligne 2
        &copy; <?= date('Y') ?> Cartoon's Animals. Tous droits réservés. // Ligne 3
    </footer>                       // Ligne 4

    <!-- Script pour désactiver la recharge de page lors de l'ajout au panier -->
    <script>                       // Ligne 7
    document.addEventListener('submit', function(event) { // Ligne 8
        // On cible uniquement les formulaires qui pointent vers add_to_cart.php
        if (event.target.action && event.target.action.includes('add_to_cart.php')) { // Ligne 10
            event.preventDefault(); // Ligne 11 : Annule envoi classique

            const form = event.target; // Ligne 13
            const formData = new FormData(form); // Ligne 14 : Création FormData
            formData.append('ajax', '1'); // Ligne 15 : Flag AJAX

            // Sélection du bouton pour feedback visuel
            const btn = form.querySelector('button'); // Ligne 18
            const originalText = btn.innerText; // Ligne 19
            
            // État de chargement
            btn.innerText = '⏳...'; // Ligne 22
            btn.disabled = true;      // Ligne 23

            // Envoi de la requête en arrière-plan
            fetch(form.action, {     // Ligne 26
                method: 'POST',     // Ligne 27
                body: formData,      // Ligne 28
                headers: {           // Ligne 29
                    'X-Requested-With': 'XMLHttpRequest' // Ligne 30
                }
            })
            .then(response => response.json()) // Ligne 33 : Parse JSON
            .then(data => {           // Ligne 34
                if (data.success) {   // Ligne 35
                    // Mise à jour dynamique du badge du panier sans recharger
                    const badge = document.querySelector('.badge'); // Ligne 37
                    if (badge) {      // Ligne 38
                        badge.innerText = data.total_items; // Ligne 39
                        // On déclenche l'animation de pulsation
                        badge.classList.remove('pulse'); // Ligne 41
                        void badge.offsetWidth; // Ligne 42 : Force reflow
                        badge.classList.add('pulse'); // Ligne 43
                    }
                    
                    // Feedback visuel de succès sur le bouton
                    btn.innerText = '✅ Ajouté'; // Ligne 47
                    btn.classList.add('btn-success'); // Ligne 48
                    
                    // On remet le bouton à son état initial après 2 secondes
                    setTimeout(() => {  // Ligne 51
                        btn.innerText = originalText; // Ligne 52
                        btn.disabled = false; // Ligne 53
                        btn.classList.remove('btn-success'); // Ligne 54
                    }, 2000);          // Ligne 55
                } else if (data.error === 'Non connecté') { // Ligne 56
                    // Si la session a expiré, on redirige vers la connexion
                    window.location.href = 'login.php'; // Ligne 58
                }
            })
            .catch(error => {         // Ligne 61
                console.error('Erreur AJAX:', error); // Ligne 62
                btn.innerText = '❌ Erreur'; // Ligne 63
                btn.disabled = false; // Ligne 64
                setTimeout(() => btn.innerText = originalText, 2000); // Ligne 65
            });
        }
    });
    </script>                       // Ligne 69
</body>                           // Ligne 70
</html>                            // Ligne 71
```

**Ligne 11** : `event.preventDefault()` empêche le rechargement de page
**Ligne 42** : `void badge.offsetWidth` force le reflow pour redéclencher l'animation CSS
**Ligne 51** : `setTimeout()` exécute le callback après 2000ms (2 secondes)

---

### includes/modal_container.php

```php
<!-- Squelette de la pop-up (Modal) -->
<div id="animalModal" class="modal"> // Ligne 2
    <div class="modal-content">     // Ligne 3
        <!-- Bouton pour fermer la pop-up -->
        <span class="close-modal">&times;</span> // Ligne 5
        
        <!-- Le contenu sera chargé ici dynamiquement via JavaScript -->
        <div id="modalBodyContent"> // Ligne 8
            <p style="padding: 20px;">Chargement des informations...</p> // Ligne 9
        </div>                      // Ligne 10
    </div>                          // Ligne 11
</div>                             // Ligne 12

<script>                           // Ligne 14
/**
 * Script pour gérer l'ouverture et la fermeture de la pop-up
 */
document.addEventListener('DOMContentLoaded', function() { // Ligne 18
    const modal = document.getElementById('animalModal'); // Ligne 19
    const closeBtn = document.querySelector('.close-modal'); // Ligne 20
    const modalBody = document.getElementById('modalBodyContent'); // Ligne 21

    // Fonction pour ouvrir la pop-up et charger les données
    window.openAnimalDetails = function(animalId) { // Ligne 24
        modal.style.display = 'block'; // Ligne 25 : Affichage modal
        modalBody.innerHTML = '<p style="padding: 20px;">Chargement des informations...</p>'; // Ligne 26

        // Appel AJAX vers le fichier PHP séparé
        fetch('animal_details.php?id=' + animalId) // Ligne 29
            .then(response => response.text()) // Ligne 30 : Récupère texte (HTML)
            .then(html => {           // Ligne 31
                modalBody.innerHTML = html; // Ligne 32 : Injection HTML
            })
            .catch(error => {         // Ligne 34
                console.error('Erreur:', error); // Ligne 35
                modalBody.innerHTML = '<p style="padding: 20px;">Une erreur est survenue lors du chargement.</p>'; // Ligne 36
            });
    };

    // Fermer la pop-up quand on clique sur la croix
    closeBtn.onclick = function() {  // Ligne 41
        modal.style.display = 'none'; // Ligne 42
    }

    // Fermer la pop-up quand on clique en dehors de la fenêtre
    window.onclick = function(event) { // Ligne 46
        if (event.target == modal) { // Ligne 47 : Clic sur fond
            modal.style.display = 'none'; // Ligne 48
        }
    }
    
    // Fonction AJAX pour ajouter/retirer des favoris sans recharger la page
    window.toggleFavorite = function(productId) { // Ligne 53
        // Envoi de la requête
        fetch('add_favorite.php', {  // Ligne 55
            method: 'POST',         // Ligne 56
            headers: {              // Ligne 57
                'Content-Type': 'application/x-www-form-urlencoded', // Ligne 58
                'X-Requested-With': 'XMLHttpRequest' // Ligne 59
            },
            body: 'product_id=' + productId + '&ajax=1' // Ligne 61
        })
        .then(response => response.json()) // Ligne 63
        .then(data => {              // Ligne 64
            if(data.success) {       // Ligne 65
                // Mise à jour du bouton dans la modale
                const btn = document.getElementById('btn-fav-' + productId); // Ligne 67
                const svg = document.getElementById('svg-fav-' + productId); // Ligne 68
                
                if (data.is_favorite) { // Ligne 70
                    btn.style.backgroundColor = '#ffe6e6'; // Ligne 71
                    btn.style.color = 'red'; // Ligne 72
                    svg.setAttribute('fill', 'red'); // Ligne 73
                    svg.setAttribute('stroke', 'red'); // Ligne 74
                } else {              // Ligne 75
                    btn.style.backgroundColor = 'white'; // Ligne 76
                    btn.style.color = 'var(--text-main)'; // Ligne 77
                    svg.setAttribute('fill', 'none'); // Ligne 78
                    svg.setAttribute('stroke', 'currentColor'); // Ligne 79
                }
                
                // Mise à jour du petit coeur indicateur sur la page principale
                const indicator = document.getElementById('fav-indicator-' + productId); // Ligne 83
                if (indicator) {      // Ligne 84
                    indicator.style.display = data.is_favorite ? 'block' : 'none'; // Ligne 85
                }
                
                // Si on est sur la page favorites.php, on peut vouloir rafraîchir ou masquer la carte
                // Optionnel: document.getElementById('card-' + productId).style.display = 'none';
            } else {                 // Ligne 89
                alert(data.error || "Une erreur est survenue."); // Ligne 91
            }
        })
        .catch(error => {            // Ligne 94
            console.error("Erreur:", error); // Ligne 95
        });
    };
});
</script>                          // Ligne 98
```

**Ligne 24** : `window.openAnimalDetails` crée une fonction globale accessible depuis n'importe où
**Ligne 30** : `response.text()` car on récupère du HTML, pas du JSON
**Ligne 47** : `event.target == modal` vérifie si on a cliqué sur le fond semi-transparent

---

## 🔒 SÉCURITÉ

### Mesures de sécurité implémentées

#### 1. Hachage des mots de passe

```php
// Lors de l'inscription
$hash = password_hash($password, PASSWORD_DEFAULT);

// Lors de la connexion
if ($user && password_verify($_POST['password'], $user['password'])) {
    // Connexion réussie
}
```

- **Algorithme** : Bcrypt via `PASSWORD_DEFAULT`
- **Coût** : Automatiquement ajusté par PHP
- **Sel** : Inclus automatiquement dans le hash

#### 2. Protection contre les injections SQL

Toutes les requêtes utilisent des **requêtes préparées** :

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
```

- **Paramètres liés** : Les valeurs sont traitées comme des données, pas du code
- **Type safety** : PDO gère automatiquement le typage

#### 3. Validation des entrées

```php
// Validation des champs obligatoires
if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price'])) {
    header('Location: admin_products.php?error=champs_manquants');
    exit();
}

// Validation des valeurs numériques
elseif ($_POST['price'] < 0) {
    header('Location: admin_products.php?error=prix_negatif');
    exit();
}
```

#### 4. Contrôle d'accès

```php
// Vérification authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Vérification droits admin
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) {
    header('Location: index.php');
    exit();
}
```

#### 5. Protection XSS

```php
// Échappement systématique des sorties
<?= htmlspecialchars($row['name']) ?>
<?= htmlspecialchars($_GET['search']) ?>
```

#### 6. Validation des uploads

```php
// Validation de l'extension
$extension = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
$extensionsAutorisees = ['png', 'jpg', 'jpeg'];

if (in_array($extension, $extensionsAutorisees)) {
    // Upload autorisé
}
```

#### 7. Protection CSRF (partielle)

- Les formulaires sensibles devraient inclure un token CSRF
- Actuellement : Protection par session uniquement

### Points de sécurité à améliorer

1. **Token CSRF** : Ajouter des tokens dans les formulaires
2. **Rate limiting** : Limiter les tentatives de connexion
3. **HTTPS** : Forcer HTTPS en production
4. **Validation email** : Vérifier le format des emails
5. **Sanitization** : Nettoyer plus rigoureusement les entrées

---

## 🎨 DESIGN ET UX

### Style Cartoon

#### Variables CSS principales

```css
:root {
    --primary: #FF5722;              /* Orange vif */
    --primary-hover: #E64A19;        /* Orange plus sombre */
    --bg-main: #FFF8E1;              /* Jaune très clair */
    --bg-card: #FFFFFF;              /* Blanc */
    --text-main: #000000;            /* Noir */
    --text-muted: #424242;           /* Gris */
    --border: #000000;               /* Bordures noires */
    --success: #4CAF50;              /* Vert */
    --shadow: 4px 4px 0px #000000;   /* Ombre dure */
    --shadow-hover: 6px 6px 0px #000000;
}
```

#### Effets caractéristiques

**Ombres dures (cartoon) :**
```css
box-shadow: 4px 4px 0px #000000;
```

**Bordures épaisses :**
```css
border: 3px solid var(--border);
```

**Coins arrondis :**
```css
border-radius: 15px;
```

**Motif de fond (demi-teintes) :**
```css
background-image: radial-gradient(#FFE082 20%, transparent 20%);
background-size: 20px 20px;
```

### Animations

#### Boutons

```css
button:active {
    transform: translate(4px, 4px);  /* Effet d'enfoncement */
    box-shadow: 0px 0px 0px #000000;
}

button:hover {
    background: var(--primary-hover);
}
```

#### Navigation

```css
nav a:hover {
    transform: scale(1.1) rotate(-2deg); /* Rebond + rotation */
    color: var(--primary);
}
```

#### Modal

```css
@keyframes modal-pop {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
```

### Responsive Design

```css
.container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2rem;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 3rem 2rem;
}
```

- **Grille adaptative** : `repeat(auto-fill, minmax(300px, 1fr))`
- **Largeur max** : 1100px pour lisibilité
- **Gap généreux** : Espacement confortable

### Accessibilité

- **Contraste** : Noir sur blanc/jaune clair
- **Taille police** : Minimum 1rem
- **Focus visible** : Bordure orange sur les inputs actifs
- **Texte alternatif** : Attributs `alt` sur les images

---

## 💪 POINTS FORTS ET AMÉLIORATIONS

### Points forts du projet

#### ✅ Architecture claire

- Séparation logique des fichiers
- Code organisé et lisible
- Commentaires explicatifs

#### ✅ Fonctionnalités complètes

- Authentification sécurisée
- Panier dynamique
- Système de favoris
- Administration complète
- Proposition par utilisateurs

#### ✅ UX soignée

- Design cartoon cohérent
- Animations fluides
- Feedback utilisateur (AJAX)
- Interface responsive

#### ✅ Sécurité

- Hachage bcrypt
- Requêtes préparées
- Protection XSS
- Contrôle d'accès

#### ✅ Base de données bien structurée

- Normalisation (3NF)
- Contraintes d'intégrité
- Relations claires
- Index appropriés

### Améliorations possibles

#### 🔧 Backend

1. **Architecture MVC complète**
   - Séparer Models, Views, Controllers
   - Utiliser un framework (Laravel, Symfony)

2. **API REST**
   - Créer des endpoints JSON
   - Séparer frontend et backend

3. **Tests unitaires**
   - PHPUnit pour les tests PHP
   - Tests d'intégration

4. **Gestion d'erreurs**
   - Page d'erreur personnalisée
   - Logging des erreurs

#### 🎨 Frontend

1. **Framework JavaScript**
   - React, Vue.js ou Angular
   - Gestion d'état (Redux, Vuex)

2. **CSS moderne**
   - Sass/SCSS pour l'organisation
   - CSS Grid avancé
   - Variables CSS dynamiques

3. **PWA (Progressive Web App)**
   - Service Worker
   - Offline support
   - Installation

#### 🗄️ Base de données

1. **Migration vers MySQL/PostgreSQL**
   - Plus robuste que SQLite
   - Meilleures performances

2. **Optimisation**
   - Index supplémentaires
   - Requêtes optimisées
   - Cache (Redis)

3. **Backup automatique**
   - Sauvegardes régulières
   - Restauration facile

#### 🔒 Sécurité

1. **Token CSRF**
   - Protection contre les attaques CSRF
   - Formulaire sécurisés

2. **Rate limiting**
   - Limiter les tentatives de connexion
   - Protection contre le brute force

3. **Validation renforcée**
   - Validation côté serveur ET client
   - Sanitization des entrées

#### 📱 Fonctionnalités

1. **Recherche avancée**
   - Filtres multiples
   - Recherche plein texte
   - Autocomplétion

2. **Notifications**
   - Email de confirmation
   - Notifications push
   - Alertes en temps réel

3. **Paiement**
   - Intégration Stripe/PayPal
   - Gestion des commandes
   - Factures PDF

4. **Reviews**
   - Avis clients
   - Notes
   - Commentaires

5. **Messagerie**
   - Chat entre utilisateurs
   - Notifications de messages
   - Historique

---

## 🎯 CONCLUSION

**Cartoon's Animals** est un projet e-commerce complet et fonctionnel qui démontre une bonne maîtrise de PHP, SQLite, et du développement web moderne. Le code est bien structuré, sécurisé, et l'interface utilisateur est soignée avec un design cartoon original.

### Points clés à retenir pour l'oral :

1. **Architecture** : Pattern MVC simplifié avec PHP pur
2. **Sécurité** : Hachage bcrypt, requêtes préparées, protection XSS
3. **Base de données** : SQLite avec 7 tables bien normalisées
4. **Fonctionnalités** : Authentification, panier, favoris, admin, AJAX
5. **Design** : Style cartoon avec CSS moderne et animations
6. **UX** : Feedback utilisateur, interface responsive, modal dynamique

Le projet est solide et peut servir de base pour des améliorations futures (framework, API, tests, etc.).

---

## 📚 RESSOURCES

- **PHP** : https://www.php.net/docs.php
- **PDO** : https://www.php.net/manual/fr/book.pdo.php
- **SQLite** : https://www.sqlite.org/docs.html
- **CSS Grid** : https://css-tricks.com/snippets/css/complete-guide-grid/
- **JavaScript Fetch** : https://developer.mozilla.org/fr/docs/Web/API/Fetch_API
- **Password Hashing** : https://www.php.net/manual/fr/function.password-hash.php

---

**Document généré pour préparation à l'oral - Bonne chance ! 🍀**
