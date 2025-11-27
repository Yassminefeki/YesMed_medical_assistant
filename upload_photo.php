<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
try {
    require_once __DIR__ . '/config/bd.php';
    // var_dump($conn); // Debug: check if connection is established (removed)
    if (!isset($conn)) {
        throw new Exception("Erreur de connexion à la base de données.");
    }

} catch (Exception $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

// Process form submission when image is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    // Get the uploaded file information
    $file = $_FILES['photo'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileError = $file['error'];
    $fileSize = $file['size'];

    // Debugging: Check the file array
    var_dump($_FILES['photo']);
    
    // Define allowed file extensions and size limit (5MB in this case)
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Extract file extension
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file
    if ($fileError !== 0) {
        $errors[] = "Une erreur est survenue lors du téléchargement de l'image. Code d'erreur: " . $fileError;
    } elseif (!in_array($fileExtension, $allowedExtensions)) {
        $errors[] = "Seules les images JPG, JPEG et PNG sont autorisées.";
    } elseif ($fileSize > $maxFileSize) {
        $errors[] = "L'image ne doit pas dépasser 5 Mo.";
    }

// If no errors, move the file to the server and update the user's profile
if (empty($errors)) {
    // Ensure the uploads directory exists
    $uploadDir = __DIR__ . '/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique name for the file to avoid conflicts
    $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
    $fileDestination = $uploadDir . $newFileName;

    // Debugging: Check if the move works
    if (!move_uploaded_file($fileTmp, $fileDestination)) {
        $errors[] = "Erreur lors du déplacement de l'image.";
    } else {
        // Update the photo in the database
        try {
            $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
            $stmt->execute(['uploads/' . $newFileName, $_SESSION['user_id']]);

            $success = "Photo de profil mise à jour avec succès.";
            // Redirect to profile.php after successful upload
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            error_log("Error updating profile photo: " . $e->getMessage());
            $errors[] = "Erreur lors de la mise à jour de la photo de profil.";
        }
    }
}
}

include __DIR__ . '/includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Changer la Photo de Profil</title>
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

    .center-page {
      width: 100%;
      min-height: 100vh;
      padding: 3rem 4rem;
      background-color: #f0f4f8;
    }

    .login-container {
      max-width: 600px;
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
      display: flex;
      flex-direction: column;
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-size: 1rem;
      color: #1e40af;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .form-group input[type="file"] {
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 1rem;
      color: #374151;
      background-color: #fff;
    }

    .btn {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.3s ease;
      margin-right: 1rem;
    }

    .btn-save {
      background-color: #10b981;
      color: white;
    }

    .btn-save:hover {
      background-color: #059669;
    }

    .btn-cancel {
      background-color: #6b7280;
      color: white;
    }

    .btn-cancel:hover {
      background-color: #4b5563;
    }

    .success-message {
      background-color: #d1fae5;
      color: #065f46;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }

    .error-messages {
      background-color: #fee2e2;
      color: #991b1b;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }

    .error-messages p {
      margin: 0.5rem 0;
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
    <h2>Changer la Photo de Profil</h2>

    <?php if (!empty($success)): ?>
      <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="error-messages">
        <?php foreach ($errors as $error): ?>
          <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="photo">Sélectionnez une nouvelle photo de profil</label>
        <input type="file" id="photo" name="photo" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-save">Télécharger</button>
      <a href="profil.php" class="btn btn-cancel">Annuler</a>
    </form>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script>
  // Vérifie s'il y a un message de succès affiché
  const successMessage = document.querySelector('.success-message');

  if (successMessage) {
    // Redirection après 3 secondes
    setTimeout(() => {
      window.location.href = "profil.php";
    }, 3000);
  }
</script>
</body>
</html>

