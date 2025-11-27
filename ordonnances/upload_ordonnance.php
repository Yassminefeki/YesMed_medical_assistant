<?php
session_start(); 
require_once '../config/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = basename($_FILES["ordonnance"]["name"]);
    $targetFile = $targetDir . uniqid() . '_' . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Vérifier si c'est une image
    $check = getimagesize($_FILES["ordonnance"]["tmp_name"]);
    if ($check === false) {
        echo "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }
    
    // Vérifier la taille du fichier (5MB max)
    if ($_FILES["ordonnance"]["size"] > 5000000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }
    
    // Autoriser certains formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "pdf"])) {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG & PDF sont autorisés.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1 && move_uploaded_file($_FILES["ordonnance"]["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO ordonnances (user_id, image_path) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $targetFile]);
        
        header('Location: gestion_ordonnances.php');
        exit;
    } else {
        echo "Désolé, une erreur est survenue lors du téléchargement de votre fichier.";
    }
}
include '../includes/header.php';
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter une Ordonnance</title>
  <style>
    /* Reset & Base */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      width: 100%;
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
    }

    /* Full-width dashboard style layout */
    .center-page {
      width: 100%;
      min-height: 100vh;
      padding: 3rem 4rem;
      background-color: #f0f4f8;
    }

    .login-container {
      max-width: 800px;
      margin: 0 auto;
      background-color: white;
      border-radius: 16px;
      padding: 2.5rem 3rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .login-container h2 {
      font-size: 2rem;
      color: #1e3a8a;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      font-size: 1rem;
      color: #4b5563;
      margin-bottom: 0.5rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.8rem;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      font-size: 1rem;
    }

    .form-actions {
      margin-top: 1.5rem;
      display: flex;
      justify-content: space-between;
    }

    .btn {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn-primary {
      background-color: #3b82f6;
      color: white;
    }

    .btn-primary:hover {
      background-color: #2563eb;
    }

    .btn-secondary {
      background-color: #e5e7eb;
      color: #374151;
    }

    .btn-secondary:hover {
      background-color: #d1d5db;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .center-page {
        padding: 1.5rem;
      }

      .login-container {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <div class="center-page">
    <div class="login-container">
      <h2>Ajouter une ordonnance</h2>

      <form method="POST" action="upload_ordonnance.php" enctype="multipart/form-data">
        <div class="form-group">
          <label for="ordonnance">Sélectionner une ordonnance (image ou PDF)</label>
          <input type="file" id="ordonnance" name="ordonnance" accept="image/*,.pdf" required>
        </div>
        
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Télécharger</button>
          <a href="gestion_ordonnances.php" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
