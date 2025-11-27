<?php
session_start(); 
require_once '../config/bd.php';

$stmt = $conn->prepare("SELECT * FROM ordonnances WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$ordonnances = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion des Ordonnances</title>
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

    .btn {
      display: inline-block;
      background-color: #3b82f6;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #2563eb;
    }

    .btn-add {
      margin-bottom: 2rem;
    }

    .ordonnances-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .ordonnance-card {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      overflow: hidden;
      transition: transform 0.2s ease;
    }

    .ordonnance-card:hover {
      transform: translateY(-4px);
    }

    .ordonnance-image img {
      width: 100%;
      height: auto;
      border-radius: 10px;
    }

    .ordonnance-details {
      padding-top: 1rem;
    }

    .ordonnance-title {
      font-size: 1.2rem;
      color: #1e3a8a;
      font-weight: bold;
      margin-bottom: 1rem;
    }

    .ordonnance-actions {
      display: flex;
      gap: 1rem;
    }

    .btn-view {
      background-color: #4caf50;
    }

    .btn-view:hover {
      background-color: #45a049;
    }

    .btn-delete {
      background-color: #ef4444;
    }

    .btn-delete:hover {
      background-color: #dc2626;
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
      <h2>Gestion des Ordonnances</h2>

      <a href="upload_ordonnance.php" class="btn btn-add">Ajouter une ordonnance</a>

      <div class="ordonnances-grid">
        <?php foreach ($ordonnances as $ordonnance): ?>
        <div class="ordonnance-card">
          <div class="ordonnance-image">
            <img src="<?= htmlspecialchars($ordonnance['image_path']) ?>" alt="Ordonnance médicale">
          </div>
          <div class="ordonnance-details">
            <p class="ordonnance-title">Ordonnance</p>
            <div class="ordonnance-actions">
              <a href="<?= htmlspecialchars($ordonnance['image_path']) ?>" target="_blank" class="btn btn-view">Voir</a>
              <a href="supprimer_ordonnance.php?id=<?= $ordonnance['id'] ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ordonnance ?');">Supprimer</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</body>
</html>
