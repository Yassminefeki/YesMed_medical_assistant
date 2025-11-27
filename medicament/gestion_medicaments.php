<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/YesMed/config/bd.php';
session_start(); // Assurez-vous que la session est démarrée

// Récupérer les médicaments pour l'utilisateur connecté
$stmt = $conn->prepare("SELECT * FROM medicaments WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$medicaments = $stmt->fetchAll();

include '../includes/header.php';
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion des Médicaments</title>
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

    /* Medicament List */
    .medicament-list {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .medicament-card {
      background-color: white;
      border-radius: 16px;
      padding: 1.5rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      width: 100%;
      max-width: 400px;
      transition: transform 0.2s ease;
    }

    .medicament-card:hover {
      transform: translateY(-4px);
    }

    .medicament-card h3 {
      font-size: 1.3rem;
      color: #2563eb;
      margin-bottom: 0.5rem;
    }

    .medicament-card p {
      color: #4b5563;
      font-size: 1rem;
    }

    .actions {
      margin-top: 1rem;
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

    /* Edit & Delete Buttons */
    .btn-edit {
      background-color: #2563eb;
      margin-right: 1rem;
    }

    .btn-edit:hover {
      background-color: #1d4ed8;
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

      .medicament-card {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="center-page">
    <div class="login-container">
      <h2>Gestion des Médicaments</h2>
      <a href="ajouter_medicament.php" class="btn">Ajouter un médicament</a>

      <?php if (count($medicaments) > 0): ?>
        <div class="medicament-list">
          <?php foreach ($medicaments as $medicament): ?>
            <div class="medicament-card">
              <h3><?= htmlspecialchars($medicament['nom']) ?></h3>
              <p><strong>Dosage:</strong> <?= htmlspecialchars($medicament['dose']) ?></p>
              <p><strong>Heure de prise:</strong> <?= htmlspecialchars($medicament['heure']) ?></p>
              <div class="actions">
                <a href="modifier_medicament.php?id=<?= $medicament['id'] ?>" class="btn btn-edit">Modifier</a>
                <a href="supprimer_medicament.php?id=<?= $medicament['id'] ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce médicament ?');">Supprimer</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>Aucun médicament trouvé.</p>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>



<?php include '../includes/footer.php'; ?>

