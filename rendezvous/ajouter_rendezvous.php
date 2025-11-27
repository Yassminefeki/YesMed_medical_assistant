<?php
session_start(); 
require_once '../config/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $specialite= $_POST['specialite'];
    $medecin = $_POST['medecin_name'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    
    $stmt = $conn->prepare("INSERT INTO rendezvous (user_id, specialite, medecin_name, date, heure) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $specialite, $medecin, $date, $heure]);
    
    header('Location: gestion_rendezvous.php');
    exit;
    
}


?>

<?php include '../includes/header.php';?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un Rendez-vous</title>
  <style>
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

    .center-page {
      width: 100%;
      min-height: 100vh;
      padding: 3rem 4rem;
    }

    .login-container {
      max-width: 1200px;
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
      gap: 1rem;
    }

    .btn {
      display: inline-block;
      background-color: #3b82f6;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.3s ease;
      text-align: center;
    }

    .btn:hover {
      background-color: #2563eb;
    }

    .btn-secondary {
      background-color: #e5e7eb;
      color: #374151;
    }

    .btn-secondary:hover {
      background-color: #d1d5db;
    }

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
      <h2>Ajouter un Rendez-vous</h2>

      <form method="POST" action="ajouter_rendezvous.php">
        <div class="form-group">
          <label for="medecin_name">Nom du médecin</label>
          <input type="text" id="medecin_name" name="medecin_name" required>
        </div>

        <div class="form-group">
          <label for="specialite">Spécialité du médecin</label>
          <input type="text" id="specialite" name="specialite" required>
        </div>

        <div class="form-group">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" required>
        </div>

        <div class="form-group">
          <label for="heure">Heure</label>
          <input type="time" id="heure" name="heure" required>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn">Ajouter</button>
          <a href="gestion_rendezvous.php" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>

<?php include '../includes/footer.php'; ?>
