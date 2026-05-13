<?php
// config.php
// Connexion à la base SQLite

$pdo = new PDO('sqlite:db.sqlite');

// Activation du mode d'erreur pour les exceptions (facilite le débogage)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ------------------------------------------------------------------
// Création automatique de la table purchase_requests si elle n'existe pas.
// Cette table stocke les demandes d'achat soumises par les utilisateurs,
// en attente de validation par un administrateur.
//
// Colonnes :
//   id           - identifiant unique de la demande
//   user_id      - l'utilisateur qui a soumis la demande
//   product_id   - l'animal concerné par la demande
//   total_price  - le prix de cet animal au moment de la demande
//   status       - état : 'en_attente', 'validee', 'refusee'
//   created_at   - date et heure de création de la demande
// ------------------------------------------------------------------
$pdo->exec("
    CREATE TABLE IF NOT EXISTS purchase_requests (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id     INTEGER NOT NULL,
        product_id  INTEGER NOT NULL,
        total_price REAL    NOT NULL,
        status      TEXT    NOT NULL DEFAULT 'en_attente',
        created_at  TEXT    NOT NULL DEFAULT (datetime('now'))
    )
");
?>