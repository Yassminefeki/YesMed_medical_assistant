<?php
// Vérifier si l'utilisateur est connect
if (!isset($_SESSION['user_id'])) {
    header('Location: /YesMed/login.php');
    exit;
}

$base_url = '/YesMed/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YesMed - Gestion Médicale</title>
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="<?= $base_url ?>index.php" class="logo-link">
                <img src="<?= $base_url ?>assets/im/logo/4.png" alt="Logo YesMed" class="logo-img">
                <span class="logo-text">YesMed</span>
            </a>
        </div>
        <nav class="main-nav">
            <div class="menu-toggle" onclick="toggleMenu()">☰</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= $base_url ?>index.php" class="nav-link">
                        
                        <span class="nav-text">Accueil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>medicament/gestion_medicaments.php" class="nav-link">
                        
                        <span class="nav-text">Médicaments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>rendezvous/gestion_rendezvous.php" class="nav-link">
                        
                        <span class="nav-text">Rendez-vous</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>ordonnances/gestion_ordonnances.php" class="nav-link">
                        
                        <span class="nav-text">Ordonnances</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>contacts/gestion_contacts.php" class="nav-link">
                        
                        <span class="nav-text">Contacts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>profile.php" class="nav-link">
                        
                        <span class="nav-text">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base_url ?>logout.php" class="nav-link">
                        
                        <span class="nav-text">Deconnection</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>


    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        // Toggle mobile menu
        function toggleMenu() {
            const navList = document.querySelector('.nav-list');
            navList.classList.toggle('active');
        }
    </script>
</body>
</html>