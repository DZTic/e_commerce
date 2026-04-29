<?php
require 'config.php';
$result = $pdo->query("SELECT * FROM products LIMIT 1");
$row = $result->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
print_r(array_keys($row));
echo "</pre>";
?>
