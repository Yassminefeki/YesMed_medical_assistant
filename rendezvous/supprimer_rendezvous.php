<?php
session_start(); // Start the session
require_once '../config/bd.php'; // Include database connection

if (!isset($_GET['id'])) {
    header('Location: gestion_rendezvous.php'); // Redirect if no ID is provided
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM rendezvous WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // Display error message
}

// Redirect to the management page after deletion
header('Location: gestion_rendezvous.php');
exit;
?>