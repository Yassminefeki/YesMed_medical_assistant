<?php
require_once 'config/bd.php'; // Use forward slashes for compatibility
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check that the fields are filled
    if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Tous les champs sont requis.";
    } else {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        try {
            // Check if the email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existingUser  = $stmt->fetch();

            if ($existingUser ) {
                $error = "Cet email est déjà utilisé.";
            } else {
                // Insert the new user
                $stmt = $conn->prepare("INSERT INTO users (nom, email, password) VALUES (:nom, :email, :password)");
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->execute();
                
                // Optionally, log the user in immediately after registration
                $_SESSION['user_id'] = $conn->lastInsertId(); // Get the last inserted ID
                $_SESSION['user_nom'] = $nom; // Store user name in session
                header('Location: index.php'); // Redirect to the main page
                exit;
            }
        } catch (PDOException $e) {
            die("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

:root {
    --primary: #4e8cff;
    --secondary: #e1f0ff;
    --accent: #f2f6fc;
    --danger: #ff4d4f;
    --text: #333;
    --bg: #ffffff;
    --radius: 15px;
    --shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    --transition: 0.3s ease;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.login-container {
    background-color: var(--bg);
    padding: 2.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 400px;
    animation: fadeIn 0.8s ease forwards;
}

.login-container h1 {
    margin-bottom: 1.5rem;
    color: var(--primary);
    text-align: center;
    font-weight: 700;
}

.form-group {
    margin-bottom: 1.2rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text);
    font-weight: 500;
}

input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: var(--radius);
    font-size: 1rem;
    transition: border var(--transition);
}

input:focus {
    border-color: var(--primary);
    outline: none;
}

button {
    width: 100%;
    padding: 0.75rem;
    background-color: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color var(--transition);
}

button:hover {
    background-color: #346fd3;
}

.alert {
    padding: 0.8rem;
    background-color: var(--danger);
    color: white;
    border-radius: var(--radius);
    margin-bottom: 1rem;
    text-align: center;
}

p {
    text-align: center;
    margin-top: 1rem;
    color: var(--text);
}

p a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

p a:hover {
    text-decoration: underline;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YesMed - Inscription</title>
</head>
<body>
    <div class="login-container">
        <h1>Inscription à YesMed</h1>
        
        <?php if (isset($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="signup.php"> <!-- Change action to the current script -->
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">S'inscrire</button>
            <p>Déjà un compte? <a href="login.php">Se connecter</a></p>
        </form>
    </div>
</body>
</html>