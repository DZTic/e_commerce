<?php
session_start();
require 'config.php';

// Security Check
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imagePath = null; // Par défaut, on n'a pas d'image

    // 1. Vérifier si un fichier a été envoyé dans le formulaire et s'il n'y a pas d'erreur lors de l'upload
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $nom = $_FILES['fichier']['name']; // Le nom original du fichier (ex: mon_chien.png)
        $tmp = $_FILES['fichier']['tmp_name']; // Le chemin temporaire où PHP a stocké le fichier
        
        // --- NOUVEAU : Validation de l'extension ---
        // On récupère l'extension du fichier (ex: png)
        $extension = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
        // Liste des extensions autorisées
        $extensionsAutorisees = ['png', 'jpg', 'jpeg'];

        if (in_array($extension, $extensionsAutorisees)) {
            // 2. Définir le chemin de destination. __DIR__ donne le dossier actuel, on y ajoute /assets/images/
            $destinationChemin = __DIR__ . '/assets/images/' . basename($nom);
            
            // 3. Déplacer le fichier du dossier temporaire vers son dossier final
            if (move_uploaded_file($tmp, $destinationChemin)) {
                // 4. Si c'est un succès, on sauvegarde le chemin relatif qui sera mis dans la base de données
                $imagePath = 'assets/images/' . basename($nom);
            }
        } else {
            // Optionnel : on pourrait stocker une erreur ici si l'extension n'est pas bonne
        }
        // -------------------------------------------
    }

    // 5. Insérer le produit dans la base de données. On ajoute l'imagePath qu'on vient de calculer (ou null s'il n'y a pas d'image)
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, age, health, character, availability, subcategory_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'], 
        $_POST['description'], 
        $_POST['price'], 
        $imagePath,
        $_POST['age'] ?? null,
        $_POST['health'] ?? null,
        $_POST['character'] ?? null,
        $_POST['availability'] ?? null,
        $_POST['subcategory_id'] ?? null
    ]);
}

// 6. On redirige l'utilisateur vers la page d'administration pour qu'il ne reste pas sur une page blanche
header('Location: admin_products.php');
exit();
?>
