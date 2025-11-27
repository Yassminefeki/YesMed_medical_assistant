<?php
session_start();
require_once __DIR__ . '/config/bd.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    // User data
    $stmt = $conn->prepare("SELECT nom, email, photo, emoji FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    // Medications for today
    $stmtMeds = $conn->prepare("SELECT * FROM medicaments WHERE user_id = ? AND DATE(created_at) = CURDATE() ORDER BY heure");
    $stmtMeds->execute([$_SESSION['user_id']]);
    $todayMeds = $stmtMeds->fetchAll();

    // Upcoming appointments
    $stmtRdv = $conn->prepare("SELECT * FROM rendezvous WHERE user_id = ? AND date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY date, heure");
    $stmtRdv->execute([$_SESSION['user_id']]);
    $upcomingAppointments = $stmtRdv->fetchAll();

    // Emotions for chart
    $stmtEmotions = $conn->prepare("SELECT emotion, intensity, DATE(created_at) as date 
                                   FROM emotions 
                                   WHERE user_id = ? 
                                   AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                                   ORDER BY created_at");
    $stmtEmotions->execute([$_SESSION['user_id']]);
    $emotionsData = $stmtEmotions->fetchAll();

    $chartLabels = [];
    $chartValues = [];
    $emotionChartData = [];
    foreach ($emotionsData as $entry) {
        $emotionChartData[$entry['date']][] = ['intensity' => $entry['intensity']];
    }
    foreach ($emotionChartData as $date => $entries) {
        $chartLabels[] = $date;
        $chartValues[] = array_sum(array_column($entries, 'intensity')) / count($entries);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $errorMessage = "Une erreur est survenue. Veuillez réessayer plus tard.";
}
?>
<?php include 'includes/header.php';?>


<!DOCTYPE html>
<html lang="fr">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord | YesMed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #dbeafe;
            --primary-lighter: #eff6ff;
            --secondary: #1e3a8a;
            --accent: #4f46e5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --dark: #1f2937;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --white: #ffffff;
            --background: #f0f4f8;
            --border-radius: 16px;
            --border-radius-sm: 8px;
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--dark);
            line-height: 1.6;
        }

        /* Header Navigation */
        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2.5rem;
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .nav-logo img {
            height: 40px;
            width: auto;
            border-radius: 10px;
            object-fit: cover;
        }

        .nav-logo h2 {
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary);
            background-color: var(--primary-lighter);
        }

        .nav-links a.active {
            color: var(--white);
            background-color: var(--primary);
        }

        /* Main Content */
        .main-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
        }

        .greeting-card {
            grid-column: span 12;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .greeting-card h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .greeting-card p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 70%;
        }

        .greeting-card::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            transition: var(--transition);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
            border-bottom: 2px solid var(--primary-lighter);
            padding-bottom: 1rem;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header h2 i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .card-header a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .card-header a:hover {
            color: var(--primary-dark);
        }

        .card-header a::after {
            content: '→';
            font-size: 1.1rem;
        }

        /* Medications Card */
        .meds-card {
            grid-column: span 6;
        }

        .medication-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            background: var(--primary-lighter);
            margin-bottom: 0.8rem;
            transition: var(--transition);
            position: relative;
        }

        .medication-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .medication-item h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.3rem;
        }

        .medication-item p {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .med-check {
            appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            background-color: var(--white);
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }

        .med-check:checked {
            background-color: var(--success);
            border-color: var(--success);
        }

        .med-check:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 0.8rem;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .med-status {
            position: absolute;
            right: 10px;
            bottom: 2px;
            font-size: 0.8rem;
            color: var(--success);
            font-weight: 500;
        }

        /* Appointments Card */
        .appointments-card {
            grid-column: span 6;
        }

        .appointment-item {
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            background: var(--primary-lighter);
            margin-bottom: 0.8rem;
            transition: var(--transition);
            border-left: 4px solid var(--primary);
        }

        .appointment-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .appointment-item h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.3rem;
        }

        .appointment-item p {
            font-size: 0.9rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .appointment-item p i {
            color: var(--primary);
        }

        /* Emotions Card */
        .emotions-card {
            grid-column: span 12;
        }

        .emotion-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .emotion-form select {
            flex: 1;
            min-width: 120px;
            padding: 0.8rem 1rem;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius-sm);
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background-color: var(--white);
            transition: var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .emotion-form select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .btn-primary {
            padding: 0.8rem 1.5rem;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius-sm);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
  

    <main class="main-content">
        <?php if (isset($errorMessage)): ?>
            <div class="card" style="grid-column: span 12; background-color: var(--danger-light); border-left: 4px solid var(--danger);">
                <p style="color: var(--danger); text-align: center;"><?php echo $errorMessage; ?></p>
            </div>
        <?php endif; ?>

        <div class="greeting-card" style="display:flex; align-items:center; gap: 15px;">
            <?php if (!empty($user['photo'])): ?>
                <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Photo de profil" style="width:50px; height:50px; border-radius:50%; object-fit:cover; border:2px solid var(--white);">
            <?php else: ?>
                <div style="width:50px; height:50px; border-radius:50%; background-color:#ccc; display:flex; align-items:center; justify-content:center; font-size:24px; color:#666; border:2px solid var(--white);">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            <h1 style="margin:0;">Bonjour, <?php echo htmlspecialchars($user['nom']) . ' ' . ($user['emoji'] ?? '👋'); ?></h1>
            <p id="dailyMotivation" style="margin-left: 10px;"></p>
        </div>

        <!-- Medications -->
        <div class="card meds-card">
            <div class="card-header">
                <h2><i class="fas fa-pills"></i> Médicaments du jour</h2>
                <a href="medicament/gestion_medicaments.php">Voir tous</a>
            </div>
            <div>
                <?php if (!empty($todayMeds)): ?>
                    <?php foreach ($todayMeds as $med): ?>
                        <div class="medication-item">
                            <div>
                                <h3><?php echo htmlspecialchars($med['nom']); ?></h3>
                                <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($med['dose']); ?> à <?php echo substr($med['heure'], 0, 5); ?></p>
                            </div>
                            <input type="checkbox" class="med-check" data-id="<?php echo $med['id']; ?>" <?php echo $med['is_taken'] ? 'checked' : ''; ?> aria-label="<?php echo $med['is_taken'] ? 'Marquer comme non pris' : 'Marquer comme pris'; ?>">
                            <span class="med-status" data-id="<?php echo $med['id']; ?>"></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>Aucun médicament prévu aujourd'hui</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Appointments -->
        <div class="card appointments-card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> Prochains RDV</h2>
                <a href="rendezvous/gestion_rendezvous.php">Voir tous</a>
            </div>
            <div>
                <?php if (!empty($upcomingAppointments)): ?>
                    <?php foreach ($upcomingAppointments as $appointment): ?>
                        <div class="appointment-item">
                            <h3><?php echo htmlspecialchars($appointment['specialite']); ?></h3>
                            <p><i class="fas fa-calendar-day"></i> <?php echo date('d/m/Y', strtotime($appointment['date'])); ?></p>
                            <p><i class="fas fa-clock"></i> <?php echo substr($appointment['heure'], 0, 5); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-check"></i>
                        <p>Aucun rendez-vous prévu</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Emotions -->
        <div class="card emotions-card">
            <div class="card-header">
                <h2><i class="fas fa-heart"></i> Suivi des émotions</h2>
            </div>
            <form class="emotion-form">
                <select id="emotionSelect" required aria-label="Choisir une émotion">
                    <option value="">Comment vous sentez-vous aujourd'hui ?</option>
                    <option value="Heureux">😊 Heureux</option>
                    <option value="Triste">😔 Triste</option>
                    <option value="Stressé">😰 Stressé</option>
                    <option value="Calme">😌 Calme</option>
                </select>
                <select id="intensitySelect" required aria-label="Intensité de l'émotion">
                    <option value="">Intensité (1-5)</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i); ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
            <div class="chart-container">
                <canvas id="emotionChart" aria-label="Graphique de suivi des émotions sur 7 jours" role="img"></canvas>
            </div>
        </div>

        <!-- Chatbot -->
        <div class="card chatbot-card" style="grid-column: span 12;">
            <div class="card-header">
                <h2><i class="fas fa-robot"></i> Assistant santé</h2>
            </div>
            <div class="chat-container">
                <div id="assistantMessages" class="chat-messages">
                    <div class="message bot-message">Bonjour <?php echo htmlspecialchars($user['nom']); ?> ! Comment puis-je vous aider aujourd'hui ?</div>
                </div>
                <div class="chat-input">
                    <input type="text" id="assistantInput" placeholder="Posez votre question sur votre santé..." aria-label="Entrez votre question pour l'assistant santé">
                    <button id="sendMessageBtn" aria-label="Envoyer le message"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Daily Motivation with animation
            const motivations = [
                "Prenez soin de vous, chaque petit pas compte ! 🌱",
                "Votre santé est votre plus grande richesse. 💎",
                "N'oubliez pas de boire de l'eau régulièrement. 💧",
                "Un esprit sain dans un corps sain ! 🧠",
                "Vous faites du bon travail dans votre suivi médical. 👏"
            ];
            
            const motivationEl = document.getElementById('dailyMotivation');
            const randomMotivation = motivations[Math.floor(Math.random() * motivations.length)];
            
            // Typing effect for motivation message
            let i = 0;
            const typeMotivation = () => {
                if (i < randomMotivation.length) {
                    motivationEl.textContent += randomMotivation.charAt(i);
                    i++;
                    setTimeout(typeMotivation, 40);
                }
            };
            
            typeMotivation();

            // Medication Checkboxes with enhanced feedback
            document.querySelectorAll('.med-check').forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const medId = checkbox.dataset.id;
                    const status = checkbox.checked ? 1 : 0;
                    const statusSpan = document.querySelector(`.med-status[data-id="${medId}"]`);
                    const medItem = checkbox.closest('.medication-item');
                    
                    statusSpan.textContent = 'Mise à jour...';
                    
                    // Visual feedback
                    if (status) {
                        medItem.style.borderLeft = '4px solid var(--success)';
                        medItem.style.backgroundColor = 'var(--success-light)';
                    } else {
                        medItem.style.borderLeft = '';
                        medItem.style.backgroundColor = 'var(--primary-lighter)';
                    }
                    
                    fetch('api/update_medication.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: medId, status })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statusSpan.textContent = status ? '✓ Pris' : '';
                            setTimeout(() => { 
                                statusSpan.textContent = ''; 
                            }, 2000);
                        } else {
                            checkbox.checked = !checkbox.checked; // Revert on error
                            statusSpan.textContent = 'Erreur: ' + (data.error || 'Échec de la mise à jour');
                            
                            // Revert visual feedback
                            if (!status) {
                                medItem.style.borderLeft = '4px solid var(--success)';
                                medItem.style.backgroundColor = 'var(--success-light)';
                            } else {
                                medItem.style.borderLeft = '';
                                medItem.style.backgroundColor = 'var(--primary-lighter)';
                            }
                            
                            setTimeout(() => { 
                                statusSpan.textContent = ''; 
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        checkbox.checked = !checkbox.checked; // Revert on error
                        statusSpan.textContent = 'Erreur réseau';
                        
                        // Revert visual feedback
                        if (!status) {
                            medItem.style.borderLeft = '4px solid var(--success)';
                            medItem.style.backgroundColor = 'var(--success-light)';
                        } else {
                            medItem.style.borderLeft = '';
                            medItem.style.backgroundColor = 'var(--primary-lighter)';
                        }
                        
                        setTimeout(() => { 
                            statusSpan.textContent = ''; 
                        }, 3000);
                    });
                });
            });

            // Emotion Form with animation
            document.querySelector('.emotion-form').addEventListener('submit', (e) => {
                e.preventDefault();
                const emotion = document.getElementById('emotionSelect').value;
                const intensity = document.getElementById('intensitySelect').value;
                
                if (!emotion || !intensity) return;
                
                const button = e.target.querySelector('button');
                const originalText = button.textContent;
                
                button.textContent = 'Enregistrement...';
                button.disabled = true;
                
                fetch('api/save_emotion.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ emotion, intensity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.textContent = '✓ Enregistré !';
                        button.style.backgroundColor = 'var(--success)';
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        button.textContent = '⚠️ Erreur';
                        button.style.backgroundColor = 'var(--danger)';
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.style.backgroundColor = '';
                            button.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    button.textContent = '⚠️ Erreur réseau';
                    button.style.backgroundColor = 'var(--danger)';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.backgroundColor = '';
                        button.disabled = false;
                    }, 2000);
                });
            });

            // Enhanced Emotion Chart
            const ctx = document.getElementById('emotionChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
            
            const chartData = {
                labels: <?php echo json_encode($chartLabels ?? ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']); ?>,
                datasets: [{
                    label: 'Intensité des émotions',
                    data: <?php echo json_encode($chartValues ?? [3, 4, 2, 5, 4, 3, 4]); ?>,
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'white',
                    pointBorderColor: 'rgba(79, 70, 229, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            };
            
            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.9)',
                            titleFont: { family: 'Poppins', size: 14, weight: 'bold' },
                            bodyFont: { family: 'Poppins', size: 13 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: function(tooltipItems) {
                                    return 'Jour: ' + tooltipItems[0].label;
                                },
                                label: function(context) {
                                    return 'Intensité: ' + context.raw + '/5';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: 'rgba(100, 116, 139, 0.8)',
                                font: { family: 'Poppins', size: 12 }
                            }
                        },
                        y: { 
                            min: 0, 
                            max: 5,
                            ticks: {
                                stepSize: 1,
                                color: 'rgba(100, 116, 139, 0.8)',
                                font: { family: 'Poppins', size: 12 }
                            },
                            grid: {
                                color: 'rgba(226, 232, 240, 0.5)'
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        });
    </script>
    <script src="assets/js/chatbot.js"></script>
</body>
</html>
