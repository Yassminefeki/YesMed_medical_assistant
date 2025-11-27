<?php
session_start(); 
require_once '..\config\bd.php';

// Vérifier si un identifiant de contact est passé dans l'URL
if (!isset($_GET['id'])) {
    header('Location: gestion_contacts.php');
    exit;
}

$id_contact = $_GET['id'];

// Vérifier si le contact existe
$stmt = $conn->prepare("SELECT * FROM contacts_urgence WHERE id = ? AND user_id = ?");
$stmt->execute([$id_contact, $_SESSION['user_id']]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    header('Location: gestion_contacts.php');
    exit;
}

// Supprimer le contact de la base de données
$stmt = $conn->prepare("DELETE FROM contacts_urgence WHERE id = ?");
$stmt->execute([$id_contact]);

// Rediriger vers la page de gestion des contacts
header('Location: gestion_contacts.php');
exit;
