<?php
session_start(); 
require_once '../config/bd.php';

if (!isset($_GET['id'])) {
    header('Location: gestion_medicaments.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM medicaments WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);

header('Location: gestion_medicaments.php');
exit;
?>