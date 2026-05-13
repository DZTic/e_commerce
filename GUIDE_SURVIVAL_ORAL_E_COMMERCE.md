# 🛡️ GUIDE DE SURVIE - ORAL E-COMMERCE "CARTOON'S ANIMALS"

Ce document est conçu pour répondre aux questions techniques d'un jury. L'objectif est de justifier vos choix technologiques et de prouver que vous comprenez chaque ligne de code.

---

## 🚀 1. LES FONDAMENTAUX (LES QUESTIONS "SÉCURITÉ")

Si le professeur vous demande : **"Comment avez-vous sécurisé votre application ?"**, répondez avec ces 3 points :

### A. Protection contre les Injections SQL
**L'argument :** *"Je n'ai jamais inséré de variables directement dans mes requêtes SQL."*
- **La technique :** Utilisation des **Requêtes Préparées** via PDO.
- **L'exemple à montrer :** 
  `$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");`
  `$stmt->execute([$_POST['username']]);`
- **L'explication :** Le `?` est un marqueur. PDO envoie la requête et les données séparément au serveur SQL. Même si l'utilisateur tape du code SQL dans le champ "username", il sera traité comme du simple texte et non comme une commande.

### B. Protection des Mots de Passe
**L'argument :** *"On ne stocke JAMAIS un mot de passe en clair en base de données."*
- **À l'inscription (`register.php`) :** Utilisation de `password_hash($password, PASSWORD_DEFAULT)`. Cela crée une empreinte unique (hash) irréversible.
- **À la connexion (`login.php`) :** Utilisation de `password_verify($password, $hash)`. PHP compare le mot de passe saisi avec le hash stocké.
- **Justification :** Si la base de données est volée, le pirate n'a que des hashs illisibles, pas les vrais mots de passe.

### C. Protection contre les failles XSS (Cross-Site Scripting)
**L'argument :** *"Je nettoie toutes les données qui proviennent de la base de données avant de les afficher."*
- **La technique :** Utilisation de `htmlspecialchars()`.
- **L'exemple :** `<?= htmlspecialchars($row['name']) ?>`
- **L'explication :** Cela transforme les caractères spéciaux (comme `<` ou `>`) en entités HTML. Si un utilisateur nomme son animal `<script>alert('Hacked')</script>`, le navigateur affichera le texte tel quel au lieu d'exécuter le script.

---

## 🛠️ 2. DÉCRYPTAGE DES PATTERNS (LE CODE RÉCURRENT)

Le prof peut pointer une ligne au hasard. Voici les blocs les plus fréquents :

### Le bloc "Session" (Haut de page)
```php
session_start();
```
- **C'est quoi ?** Initialise ou reprend une session utilisateur.
- **Pourquoi ?** Pour que le serveur se souvienne de qui est connecté d'une page à l'autre (via un cookie de session). Sans ça, on devrait se reconnecter à chaque clic.

### Le bloc "Vérification Admin" (Pages admin)
```php
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); }
```
- **L'explication :** 
  1. On récupère l'ID de l'utilisateur dans la session.
  2. On demande à la BDD si cet ID a la valeur `is_admin = 1`.
  3. `fetchColumn()` récupère directement la valeur de la première colonne.
  4. Si c'est `0` (false), on redirige vers l'accueil.

### Le bloc "Upload d'Image" (`add_products.php`)
```php
if (move_uploaded_file($tmp, $destinationChemin)) {
    $imagePath = 'assets/images/' . basename($nom);
}
```
- **L'explication :** 
  - `$_FILES['fichier']['tmp_name']` : Le fichier est d'abord stocké dans un dossier temporaire par PHP.
  - `move_uploaded_file()` : Déplace le fichier du dossier temporaire vers notre dossier `/assets/images/`.
  - On ne stocke pas l'image elle-même en BDD, mais seulement le **chemin** (le texte) pour ne pas alourdir la base de données.

---

## 🗺️ 3. ANALYSE DES FLUX (LE CHEMIN DES DONNÉES)

### Flux : "Ajouter un animal au panier"
1. **HTML** : L'utilisateur clique sur le bouton $\rightarrow$ Formulaire POST vers `add_to_cart.php`.
2. **PHP (`add_to_cart.php`)** :
   - Vérifie si l'utilisateur est connecté (`$_SESSION['user_id']`).
   - Vérifie si l'animal est déjà dans le panier (requête `SELECT` sur `cart_items`).
   - Vérifie si l'animal n'est pas déjà vendu (`is_sold = 0` dans `products`).
   - Si OK $\rightarrow$ `INSERT INTO cart_items`.
3. **AJAX** : Si la requête vient de JS, PHP renvoie un `json_encode(['success' => true, ...])` au lieu de rediriger.

### Flux : "Proposer un animal" (`propose_animal.php`)
1. **Validation** : Le script vérifie que TOUS les champs sont remplis et que le prix/âge ne sont pas négatifs.
2. **Upload** : L'image est vérifiée (extension jpg/png) puis déplacée via `move_uploaded_file()` vers `/assets/images/`.
3. **Insertion** : Les données sont insérées en BDD via une requête préparée.

### Flux : "Gérer les favoris" (`add_favorite.php`)
1. **Toggle** : Le script vérifie si l'animal est déjà en favori.
2. **Action** : S'il y est $\rightarrow$ `DELETE`, s'il n'y est pas $\rightarrow$ `INSERT`.
3. **AJAX** : Le serveur répond en JSON (`is_favorite: true/false`), permettant au cœur de changer de couleur instantanément sans recharger la page.

### Flux : "Valider une adoption" (`process_order.php`)
C'est le cœur du système de gestion admin :
1. **Décision** : L'admin clique sur "Valider" ou "Refuser".
2. **Si Validé** :
   - Le statut de la demande passe à `validee`.
   - L'animal est marqué `is_sold = 1` (disparaît de la boutique).
   - **Nettoyage Global** : L'animal est supprimé des paniers de TOUS les utilisateurs (car il n'est plus disponible).
   - **Concurrence** : Toutes les autres demandes en attente pour ce même animal sont automatiquement refusées.
3. **Si Refusé** : Le statut passe à `refusee`, l'animal reste disponible.

---

## 📖 4. LEXIQUE DE SURVIE (FONCTIONS PHP)

| Fonction | Ce qu'il faut répondre à l'oral |
| :--- | :--- |
| `PDO` | "L'interface qui permet de communiquer avec la base de données SQLite de façon sécurisée." |
| `fetchAll()` | "Récupère toutes les lignes d'un résultat SQL sous forme de tableau PHP." |
| `fetchColumn()` | "Récupère uniquement la valeur d'une seule colonne (utile pour compter ou vérifier un booléen)." |
| `header('Location: ...')` | "Envoie une instruction au navigateur pour rediriger l'utilisateur vers une autre page." |
| `implode(" AND ", $conditions)` | "Transforme un tableau de conditions SQL en une chaîne de caractères séparée par ' AND '." |
| `number_format($price, 2)` | "Formate le nombre pour afficher exactement 2 décimales (format monétaire)." |
| `$_SESSION` | "Variable superglobale qui stocke des données côté serveur liées à un utilisateur spécifique." |
| $_POST / $_GET | "Données envoyées via un formulaire (POST) ou via l'URL (GET)." |
| `query()` | "Exécute une requête SQL simple sans paramètres. À utiliser uniquement quand il n'y a aucune variable utilisateur." |
| `$stmt` | "L'objet (PDOStatement) résultant d'une requête préparée. Il sert de pont pour envoyer les données et récupérer les résultats." |

---

## 🚩 5. QUESTIONS PIÈGES DU PROF

**Q1 : "Pourquoi avoir choisi SQLite plutôt que MySQL ?"**
- **R :** *"SQLite est léger, sans serveur, et stocké dans un seul fichier. Pour un projet de cette taille, c'est beaucoup plus simple à déployer et suffisant en termes de performance."*

**Q2 : "Que se passe-t-il si je change l'ID d'un produit manuellement dans l'URL d'une page de suppression ?"**
- **R :** *"C'est pour cela que j'ai ajouté des vérifications de droits admin au début de chaque fichier sensible. Même si l'ID est correct, si la session n'est pas admin, l'accès est refusé."*

**Q3 : "Votre site est-il responsive ?"**
- **R :** *"Oui, j'utilise CSS Grid et des Media Queries pour que la grille de produits s'adapte automatiquement à la taille de l'écran (1 colonne sur mobile, plusieurs sur desktop)."*

**Q4 : "Comment fonctionne votre système de filtres ?"**
- **R :** *"Je construis la requête SQL de manière dynamique. Je commence avec une base, puis j'ajoute des clauses `WHERE` seulement si l'utilisateur a sélectionné une catégorie ou fait une recherche, en utilisant un tableau de paramètres pour rester sécurisé."*
