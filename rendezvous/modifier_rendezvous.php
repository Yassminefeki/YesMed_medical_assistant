<?php
session_start(); // Start the session
require_once '../config/bd.php'; // Include database connection

if (!isset($_GET['id'])) {
    header('Location: gestion_rendezvous.php'); // Redirect if no ID is provided
    exit;
}

// Fetch the appointment details
$stmt = $conn->prepare("SELECT * FROM rendezvous WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$rendezvous = $stmt->fetch();

if (!$rendezvous) {
    header('Location: gestion_rendezvous.php'); // Redirect if appointment not found
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $description = $_POST['description'];
    
    try {
        $stmt = $conn->prepare("UPDATE rendezvous SET date = ?, heure = ?, description = ? WHERE id = ?");
        $stmt->execute([$date, $heure, $description, $_GET['id']]);
        
        header('Location: gestion_rendezvous.php'); // Redirect after successful update
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Display error message
    }
}

include '../includes/header.php'; // Include the header
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modifier le Rendez-vous</title>
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
      font-size: 1rem;
      color: #4b5563;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #d1d5db;
      border-radius: 10px;
      background-color: #f9fafb;
      transition: border-color 0.3s ease;
    }

    .form-group input:focus {
      border-color: #2563eb;
      outline: none;
    }

    button[type="submit"], .btn {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      background-color: #3b82f6;
      color: white;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover, .btn:hover {
      background-color: #2563eb;
    }

    .form-actions {
      margin-top: 1.5rem;
      display: flex;
      justify-content: space-between;
    }

    .btn-secondary {
      background-color: #e5e7eb;
      color: #1f2937;
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
      <h2>Modifier le Rendez-vous</h2>

      <form method="POST" action="modifier_rendezvous.php?id=<?= $rendezvous['id'] ?>">
        <div class="form-group">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" value="<?= htmlspecialchars($rendezvous['date']) ?>" required>
        </div>

        <div class="form-group">
          <label for="heure">Heure</label>
          <input type="time" id="heure" name="heure" value="<?= htmlspecialchars($rendezvous['heure']) ?>" required>
        </div>

        <div class="form-actions">
          <button type="submit">Enregistrer</button>
          <a href="gestion_rendezvous.php" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
