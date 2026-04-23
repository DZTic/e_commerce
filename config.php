<?php
// Connexion à la base SQLite
$pdo = new PDO('sqlite:database.sqlite');
// Activation du mode d'erreur pour les exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>