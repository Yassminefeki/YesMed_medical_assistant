<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YesMed - Votre santé simplifiée</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg custom-hero-navbar">
        <div class="container-fluid justify-content-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets\im\logo\4.png" class="logo-img" alt="YesMed Logo">
                <span class="logo-text ms-2">YesMed</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#about">Fondatrice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#temoignages">Témoignages</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-white" href="signup.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="profil.php">              </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Icon -->
    <div class="profile-icon" id="profileIcon">
        <i class="fas fa-user"></i>
    </div>

    <!-- Profile Menu (hidden by default) -->
    <div class="position-fixed end-0 p-3" style="top: 80px; display: none; z-index: 999;" id="profileMenu">
        <div class="card shadow" style="width: 250px;">
            <div class="card-body text-center">
                <div id="loggedOutMenu">
                    <h5 class="card-title">Connectez-vous</h5>
                    <a href="login.php" class="btn btn-primary w-100 mb-2">Connexion</a>
                    <a href="signup.php" class="btn btn-outline-primary w-100">Inscription</a>
                </div>
                <div id="loggedInMenu" style="display: none;">
                    <img src="assets/im/logo/1.png" class="rounded-circle mb-3" width="80" height="80" alt="User Profile">
                    <h6 id="usernameDisplay">Nom Utilisateur</h6>
                    <a href="profile.php" class="btn btn-sm btn-outline-secondary w-100 mb-2">Mon Profil</a>
                    <a href="logout.php" class="btn btn-sm btn-danger w-100">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold mb-4">YesMed</h1>
                    <p class="lead mb-4">La plateforme innovante qui révolutionne la gestion de votre santé au quotidien.</p>
                    <a href="#services" class="btn btn-primary">Nos Services</a>
                    <a href="#about" class="btn btn-outline-primary">En savoir plus</a>
                </div>
                <div class="col-md-6">
                    <img src="assets\im\Pills.png" alt="Application YesMed" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" style="background-color: var(--white);">
        <div class="container">
        
        </div>
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="counter-item">
                        <span class="counter" data-target="15000">0</span>+
                    </div>
                    <p>Utilisateurs satisfaits</p>
                </div>
                <div class="col-md-3">
                    <div class="counter-item">
                        <span class="counter" data-target="500">0</span>+
                    </div>
                    <p>Professionnels de santé</p>
                </div>
                <div class="col-md-3">
                    <div class="counter-item">
                        <span class="counter" data-target="24">0</span>/7
                    </div>
                    <p>Disponibilité</p>
                </div>
                <div class="col-md-3">
                    <div class="counter-item">
                        <span class="counter" data-target="98">0</span>%
                    </div>
                    <p>Satisfaction client</p>
                </div>
            </div>
        </div>
    </section>

<section id="services">
  <h2 class="text-center mb-5">Our services</h2>
  <div class="services-row">
    <div class="service">
      <img src="assets\im\Medicines.png" alt="Gestion des médicaments">
      <div class="service-text">
        <h5>Gestion des médicaments</h5>
        <p>Suivez votre traitement et recevez des rappels pour vos prises de médicaments.</p>
      </div>
    </div>
    <div class="service">
      <img src="assets\im\ordonnace.png" alt="Ordonnances numériques">
      <div class="service-text">
        <h5>Ordonnances numériques</h5>
        <p>Stockez et gérez toutes vos ordonnances médicales au même endroit.</p>
      </div>
    </div>
    <div class="service">
      <img src="assets\im\Laptop Displaying Health Website 3D Scene.png" alt="Assistant virtuel">
      <div class="service-text">
        <h5>Assistant virtuel</h5>
        <p>Posez vos questions à notre chatbot intelligent disponible 24/7.</p>
      </div>
    </div>
  </div>
  <div class="services-row">
    <div class="service">
      <img src="assets\im\heart.png" alt="Suivi émotionnel">
      <div class="service-text">
        <h5>Suivi émotionnel</h5>
        <p>Exprimez votre humeur chaque jour et suivez votre bien-être mental.</p>
      </div>
    </div>
    <div class="service">
      <img src="assets\im\bell1.png" alt="Rappels automatiques">
      <div class="service-text">
        <h5>Rappels automatiques</h5>
        <p>Recevez des notifications avant vos prises de médicaments et vos rendez-vous.</p>
      </div>
    </div>
  </div>
</section>





    <!-- Founder Section -->
    <section id="about" class="py-5 founder-section">
        <div class="container">
            <h2 class="text-center mb-5">Notre Fondatrice</h2>
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <img src="assets\im\yassminefeki.png" alt="Yassmine Feki" class="rounded-circle img-fluid mb-4" style="max-width: 250px;">
                    <h3>Yassmine Feki</h3>
                    <p class="text-muted">Fondatrice & CEO</p>
                </div>
                <div class="col-md-8">
                    <div class="about-text">
                        <p class="lead">"J'ai créé YesMed avec la conviction que la technologie peut rendre la gestion de la santé plus simple et plus accessible pour tous."</p>
                        <p>Diplômée en Informatique de la Faculté des Sciences, Yassmine a combiné son expertise technique avec sa passion pour la santé pour développer une plateforme qui répond aux besoins réels des patients et des professionnels de santé.</p>
                        <p>Son engagement pour l'innovation dans le domaine de la e-santé a été récompensé par plusieurs prix nationaux.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="temoignages" class="py-5 testimonial-section">
        <div class="container">
            <h2 class="text-center mb-5">Ce qu'ils disent de nous</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <p class="card-text">"YesMed a simplifié la gestion des rendez-vous pour mes patients. Un gain de temps considérable!"</p>
                            <div class="d-flex align-items-center mt-3">
                                <img src="assets/im/MelekFeki.jpg" class="rounded-circle me-3" width="50" height="50" alt="Dr. Melek Feki">
                                <div>
                                    <h6 class="mb-0">Dr. Melek Feki</h6>
                                    <small>Allergologue</small>
                                    <div class="rating">
                                        ★★★★★ <span>5/5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <p class="card-text">"YesMed simplifie la gestion des ordonnances et réduit les erreurs. Gain de temps garanti !"</p>
                            <div class="d-flex align-items-center mt-3">
                                <img src="assets/im/NafissaKammoun.jpg" class="rounded-circle me-3" width="50" height="50" alt="Dr. Nafissa Kammoun">
                                <div>
                                    <h6 class="mb-0">Dr. Nafissa Kammoun</h6>
                                    <small>Pharmacienne</small>
                                    <div class="rating">
                                        ★★★★★ <span>5/5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <p class="card-text">"Une interface tellement intuitive que même ma grand-mère l'utilise sans problème!"</p>
                            <div class="d-flex align-items-center mt-3">
                                <img src="assets/im/MaimounaFeki.jpg" class="rounded-circle me-3" width="50" height="50" alt="Maimouna Feki">
                                <div>
                                    <h6 class="mb-0">Maimouna Feki</h6>
                                    <small>Patiente</small>
                                    <div class="rating">
                                        ★★★★★ <span>4,5/5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>YesMed</h5>
                    <p>Votre partenaire santé au quotidien.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">Contactez-nous</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Réseaux sociaux</h5>
                    <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <hr style="border-color: var(--light-blue);">
            <div class="text-center">
                <p class="mb-0">© 2023 YesMed. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile menu toggle
        const profileIcon = document.getElementById('profileIcon');
        const profileMenu = document.getElementById('profileMenu');

        profileIcon.addEventListener('click', function () {
            profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (event) {
            if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.style.display = 'none';
            }
        });

        // Counter animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            const speed = 1000;

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animateCounters, 1);
                } else {
                    counter.innerText = target;
                }
            });
        }

        // Trigger counter animation when section is visible
        window.addEventListener('scroll', function () {
            const statsSection = document.querySelector('.py-5');
            if (!statsSection) return;

            const position = statsSection.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;

            if (position < screenPosition) {
                animateCounters();
            }
        });

        // Check login status (example)
        function checkLoginStatus() {
            // Replace with your actual logic
            const isLoggedIn = false;

            if (isLoggedIn) {
                document.getElementById('loggedOutMenu').style.display = 'none';
                document.getElementById('loggedInMenu').style.display = 'block';
            }
        }

        // Check login status on page load
        window.onload = checkLoginStatus;
    </script>
</body>
</html>