<?php
http_response_code(404); // Important pour maintenir le statut 404
include 'includes/header.php';
?>

<!-- Contenu de la page d'erreur ici -->
<div class="error-container">
<style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #FEFEFE, #0B0F4B, #191D55, #9EA0B7, #0B0F4B);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        .error-container {
            text-align: center;
            background: rgba(25, 29, 85, 0.7);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            max-width: 90%;
            width: 600px;
            animation: fadeIn 1s ease;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #9EA0B7;
            margin: 0;
            line-height: 1;
            text-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .error-title {
            font-size: 2rem;
            color: #FEFEFE;
            margin: 1rem 0;
        }

        .error-message {
            color: #9EA0B7;
            margin-bottom: 2rem;
        }

        .error-image {
            width: 150px;
            height: 150px;
            margin: 0 auto 2rem;
            opacity: 0.8;
        }

        .error-image svg {
            width: 100%;
            height: 100%;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            min-width: 140px;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <img src="yesmed-logo.png" alt="YesMed Logo" class="yesmed-logo hero-logo">
        
        <div class="error-image">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#9EA0B7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Page non trouvée</h2>
        <p class="error-message">La page que vous recherchez n'existe pas ou a été déplacée.</p>
        
        <div class="buttons">
            <a href="javascript:history.back()" class="btn">Retour</a>
            <a href="index.html" class="btn btn-primary">Accueil</a>
        </div>
    </div>
</body>
</html>
</div>

<?php
include 'includes/footer.php';
?>