<?php
session_start(); 
require_once '..\config\bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $tel = $_POST['tel'];
    
    $stmt = $conn->prepare("INSERT INTO contacts_urgence (user_id, nom, tel) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $nom, $tel]);
    
    header('Location: gestion_contacts.php');
    exit;
}

include '../includes/header.php';
?>

<style>
    .login-container {
    max-width: 500px;
    margin: 4rem auto;
    background-color: white;
    padding: 2.5rem 2rem;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

.login-container h2 {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    color: #1e3a8a;
    text-align: center;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #1e40af;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    border-color: #3b82f6;
    outline: none;
}

button[type="submit"] {
    background-color: #3b82f6;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-right: 1rem;
}

button[type="submit"]:hover {
    background-color: #2563eb;
}

a.btn {
    display: inline-block;
    background-color: #9ca3af;
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

a.btn:hover {
    background-color: #6b7280;
}

</style>

<div class="login-container">
    <h2>Ajouter un contact</h2>

    <form method="POST" action="ajouter_contact.php">
        <div class="form-group">
            <label for="nom">Nom du contact</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="tel">Numéro de téléphone</label>
            <input type="tel" id="tel" name="tel" required>
        </div>
        
        <button type="submit">Ajouter</button>
        <a href="gestion_contacts.php" class="btn">Annuler</a>
    </form>
</div>
