    <?php
    session_start(); 
    require_once '../config/bd.php';

    if (!isset($_GET['id'])) {
        header('Location: gestion_medicaments.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM medicaments WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $medicament = $stmt->fetch();

    if (!$medicament) {
        header('Location: gestion_medicaments.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $dose = $_POST['dose'];
        $heure = $_POST['heure'];
        
        $stmt = $conn->prepare("UPDATE medicaments SET nom = ?, dose = ?, heure = ? WHERE id = ?");
        $stmt->execute([$nom, $dose, $heure, $_GET['id']]);
        
        header('Location: gestion_medicaments.php');
        exit;
    }
    include '../includes/header.php';
    ?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modifier le médicament</title>
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

    /* Form Elements */
    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      color: #374151;
      margin-bottom: 0.5rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      font-size: 1rem;
      color: #374151;
    }

    .form-group input:focus {
      border-color: #3b82f6;
      outline: none;
    }

    /* Submit & Cancel Buttons */
    button[type="submit"], .btn {
      display: inline-block;
      background-color: #3b82f6;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      margin-top: 1.5rem;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover, .btn:hover {
      background-color: #2563eb;
    }

    /* Cancel Button */
    .btn {
      background-color: #ef4444;
      margin-left: 1rem;
    }

    .btn:hover {
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
      <h2>Modifier le médicament</h2>

      <form method="POST" action="modifier_medicament.php?id=<?= $medicament['id'] ?>">
        <div class="form-group">
          <label for="nom">Nom du médicament</label>
          <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($medicament['nom']) ?>" required>
        </div>
        
        <div class="form-group">
          <label for="dose">Dosage</label>
          <input type="text" id="dose" name="dose" value="<?= htmlspecialchars($medicament['dose']) ?>" required>
        </div>

        <div class="form-group">
          <label for="heure">Heure de prise</label>
          <input type="time" id="heure" name="heure" value="<?= htmlspecialchars($medicament['heure']) ?>" required>
        </div>
        
        <button type="submit">Enregistrer</button>
        <a href="gestion_medicaments.php" class="btn">Annuler</a>
      </form>
    </div>
  </div>

</body>
</html>
m>
    </div>
