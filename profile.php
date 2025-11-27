<?php
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user data
try {
    $stmt = $conn->prepare("SELECT nom, email, photo FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    error_log("Profile error: " . $e->getMessage());
    die("Une erreur est survenue");
}

// Handle form submission
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validation
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Un email valide est requis.";
    }

    // Handle file upload for photo
    $photoPath = $user['photo']; // keep the current photo by default

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = $_FILES['photo']['name'];
        $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);
        $photoNewName = uniqid('photo_', true) . '.' . $photoExtension;
        $photoDirectory = __DIR__ . '/uploads/';
        $photoPath = 'uploads/' . $photoNewName;

        // Validate file type (optional, adjust as needed)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($photoExtension, $allowedExtensions)) {
            $errors[] = "Format de photo non autorisé.";
        } else {
            // Move the file to the upload directory
            if (!move_uploaded_file($photoTmpPath, $photoDirectory . $photoNewName)) {
                $errors[] = "Erreur lors du téléchargement de la photo.";
            }
        }
    }

    // Check if email is already used by another user
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        }
    }

    // Validate password (if provided)
    if (!empty($password) || !empty($confirm_password)) {
        if (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
    }

    // Update the user if no errors
    if (empty($errors)) {
        try {
            // Update the user profile
            $stmt = $conn->prepare("UPDATE users SET nom = ?, email = ?, photo = ? WHERE id = ?");
            $stmt->execute([$nom, $email, $photoPath, $_SESSION['user_id']]);

            // Update password if provided
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
            }

            $success = "Profil mis à jour avec succès.";
            $user['nom'] = $nom;
            $user['email'] = $email;
            $user['photo'] = $photoPath;
        } catch (PDOException $e) {
            error_log("Profile update error: " . $e->getMessage());
            $errors[] = "Erreur lors de la mise à jour du profil.";
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="center-page">
    <div class="login-container">
        <h2>Modifier Votre Profil</h2>

        <?php if ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="profile-picture">
            <img src="<?= htmlspecialchars($user['photo'] ?? 'default-photo.jpg') ?>" alt="Photo de profil" class="profile-img">
            <a href="upload_photo.php" class="btn btn-edit">Changer la photo</a>
        </div>

        <form method="POST" enctype="multipart/form-data" class="profile-form">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Nouveau Mot de Passe</label>
                <input type="password" id="password" name="password" placeholder="Laisser vide pour conserver l'ancien mot de passe">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le Mot de Passe</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <div class="actions">
                <a href="index.php" class="btn btn-cancel">Annuler</a>
                <button type="submit" class="btn btn-save">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<style>
/* Base Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body, html {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f0f4f8;
  width: 100%;
  height: 100%;
}

/* Layout */
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
  text-align: center;
}

/* Form Styles */
.profile-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-weight: 600;
  color: #1e40af;
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="file"] {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Profile Picture */
.profile-picture {
  text-align: center;
  margin-bottom: 2rem;
}

.profile-img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 1rem;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

.btn-edit {
  display: block;
  margin: 0 auto;
  background-color: #2563eb;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  text-align: center;
  transition: background-color 0.3s ease;
  border: none;
  cursor: pointer;
}

.btn-edit:hover {
  background-color: #1d4ed8;
}

/* Buttons */
.btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  text-align: center;
  transition: background-color 0.3s ease;
  border: none;
  cursor: pointer;
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

/* Messages */
.success-message {
  background-color: #d1fae5;
  color: #065f46;
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
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
  margin: 0.3rem 0;
}

/* Responsive */
@media (max-width: 768px) {
  .center-page {
    padding: 1.5rem;
  }

  .login-container {
    padding: 1.5rem;
  }

  .profile-img {
    width: 120px;
    height: 120px;
  }

  .actions {
    flex-direction: column;
    gap: 1rem;
  }
}
</style>
