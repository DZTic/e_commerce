<?php
// propose_animal.php
// Ce fichier permet à n'importe quel utilisateur connecté (même non admin)
// de proposer son propre animal à l'adoption (ajout direct dans la base de données).

session_start();
require 'config.php';

// Vérification de la session : l'utilisateur doit être connecté pour proposer un animal.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = ''; // Variable pour afficher un message de succès ou d'erreur

// Récupération des catégories et sous-catégories pour le menu déroulant du formulaire
$subcategories = $pdo->query("SELECT s.*, c.name as cat_name FROM subcategories s JOIN categories c ON s.category_id = c.id ORDER BY c.name, s.name")->fetchAll();

// Traitement du formulaire lorsque l'utilisateur soumet ses données via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imagePath = null; // Par défaut, on n'a pas d'image

    // 1. Gestion de l'upload de l'image
    // On vérifie qu'un fichier a été envoyé et qu'il n'y a eu aucune erreur lors du transfert
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $nom = $_FILES['fichier']['name'];
        $tmp = $_FILES['fichier']['tmp_name'];
        
        // Validation de l'extension pour des raisons de sécurité
        $extension = strtolower(pathinfo($nom, PATHINFO_EXTENSION));
        $extensionsAutorisees = ['png', 'jpg', 'jpeg'];

        if (in_array($extension, $extensionsAutorisees)) {
            // Définition du chemin final de l'image
            $destinationChemin = __DIR__ . '/assets/images/' . basename($nom);
            
            // On déplace le fichier du dossier temporaire de PHP vers notre dossier d'images
            if (move_uploaded_file($tmp, $destinationChemin)) {
                $imagePath = 'assets/images/' . basename($nom);
            }
        }
    }

    // --- NOUVEAU : Validation des données ---
    // On vérifie que tous les champs obligatoires sont bien remplis
    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || 
        empty($_POST['subcategory_id']) || empty($_POST['age']) || 
        empty($_POST['health']) || empty($_POST['character']) || !isset($_FILES['fichier']) || $_FILES['fichier']['error'] !== 0) {
        
        $message = "Tous les champs sont obligatoires, y compris l'image !";
        $success = false;
    } 
    // On vérifie que le prix n'est pas négatif
    elseif ($_POST['price'] < 0) {
        $message = "Le prix ne peut pas être négatif.";
        $success = false;
    }
    // On vérifie que l'âge n'est pas négatif
    elseif ($_POST['age'] < 0) {
        $message = "L'âge ne peut pas être négatif.";
        $success = false;
    }
    else {
        // Insertion des informations de l'animal dans la base de données si tout est OK
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, age, health, character, subcategory_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $success = $stmt->execute([
            $_POST['name'], 
            $_POST['description'], 
            $_POST['price'], 
            $imagePath,
            $_POST['age'],
            $_POST['health'],
            $_POST['character'],
            $_POST['subcategory_id']
        ]);

        if ($success) {
            $message = "Votre animal a été ajouté avec succès !";
        } else {
            $message = "Une erreur est survenue lors de l'ajout.";
        }
    }

    // Affichage d'un message selon le résultat de l'insertion
    if ($success) {
        $message = "Votre animal a été ajouté avec succès ! Il est maintenant visible dans la boutique.";
    } else {
        $message = "Une erreur est survenue lors de l'ajout de votre animal.";
    }
}

include 'includes/header.php';
?>

<!-- Interface utilisateur pour ajouter un animal -->
<div class="container" style="max-width: 600px; margin: 4rem auto;">
    <h1 style="text-align: center; margin-bottom: 1rem;">Proposer un animal</h1>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Remplissez ce formulaire pour ajouter un animal à faire adopter sur la plateforme.</p>
    
    <!-- Zone de message de confirmation/erreur -->
    <?php if ($message): ?>
        <div style="background: var(--success); color: white; padding: 1rem; border-radius: 10px; border: 3px solid var(--border); margin-bottom: 2rem; text-align: center; box-shadow: var(--shadow);">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <!-- Formulaire d'ajout : enctype="multipart/form-data" est indispensable pour envoyer des fichiers -->
        <form method="post" action="propose_animal.php" enctype="multipart/form-data">
            <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Nom de l'animal</label>
            <input type="text" name="name" placeholder="Ex: Rex" required>
            
            <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Description</label>
            <!-- Zone de texte libre pour décrire l'animal -->
            <textarea name="description" placeholder="Parlez-nous un peu de lui..." rows="4" required style="width: 100%; padding: 1rem; border: 3px solid var(--border); border-radius: 10px; margin-bottom: 1.5rem; resize: vertical; font-size: 1rem; box-shadow: inset 2px 2px 0px rgba(0,0,0,0.1); font-family: inherit; font-weight: bold;"></textarea>
            
            <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Prix d'adoption (EUR)</label>
            <input type="number" step="0.01" name="price" placeholder="Ex: 50.00" min="0" required>

            <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Espèce / Catégorie</label>
            <select name="subcategory_id" required>
                <option value="">-- Choisir --</option>
                <!-- On liste dynamiquement toutes les sous-catégories depuis la base de données -->
                <?php foreach ($subcategories as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['cat_name']) ?> - <?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Âge (ans)</label>
                <input type="number" name="age" placeholder="Ex: 3" min="0" required>
            </div>

            <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Santé</label>
            <input type="text" name="health" placeholder="Ex: Vacciné, en pleine forme" required>

            <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem;">Caractère</label>
            <input type="text" name="character" placeholder="Ex: Très affectueux, calme" required>

            <label for="fichier" style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem; margin-top: 1rem;">Image de l'animal (jpeg, png, jpg)</label>
            <input type="file" id="fichier" name="fichier" accept=".jpg,.jpeg,.png" required>

            <button type="submit" style="margin-top: 1rem;">Proposer cet animal</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
