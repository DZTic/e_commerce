<?php
session_start();
require 'config.php';

// Security Check
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetchColumn()) { header('Location: index.php'); exit(); }

if (isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header('Location: admin_users.php');
exit();
