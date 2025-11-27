<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$error = ''; // Initialize error variable

require_once 'C:\xampp\htdocs\YesMed\config\bd.php';

if (!isset($conn)) {
    die("Database connection not established");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $stmt = $conn->prepare("SELECT id, nom, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion: PP" . $e->getMessage();
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
    <title>YesMed - Connexion</title>
<body>
    <div class="login-container">
        <h1>Connexion à YesMed</h1>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Se connecter</button>
            <p>Pas encore de compte? <a href="signup.php">S'inscrire</a></p>
        </form>
    </div>
</body>
</html>