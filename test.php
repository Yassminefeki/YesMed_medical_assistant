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

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $errorMessage = "Une erreur est survenue. Veuillez réessayer plus tard.";
}
?>
<?php include 'includes/header.php';?>

<main class="main-content">
    <?php if (isset($errorMessage)): ?>
        <div class="card" style="grid-column: span 12; background-color: var(--danger-light); border-left: 4px solid var(--danger);">
            <p style="color: var(--danger); text-align: center;"><?php echo $errorMessage; ?></p>
        </div>
    <?php endif; ?>

    <div class="greeting-card">
        <div class="profile-container">
            <?php if (!empty($user['photo'])): ?>
                <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Photo de profil" class="profile-photo">
            <?php else: ?>
                <div class="profile-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            <div class="greeting-text">
                <h1>Bonjour, <?php echo htmlspecialchars($user['nom']) . ' ' . ($user['emoji'] ?? '👋'); ?></h1>
                <p id="dailyMotivation"></p>
            </div>
        </div>
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

    <!-- Emotions and Chatbot Side by Side -->
    <div class="side-by-side-container">
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
        </div>

        <!-- Chatbot -->
        <div class="card chatbot-card">
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily motivation quotes
    const motivations = [
        "Prendre soin de sa santé est un acte d'amour envers soi-même.",
        "Petit à petit, chaque effort compte pour votre bien-être.",
        "Votre santé mérite votre attention chaque jour.",
        "Chaque pas vers une meilleure santé est une victoire.",
        "Un esprit sain dans un corps sain : prenez soin des deux !"
    ];
    document.getElementById('dailyMotivation').textContent = motivations[Math.floor(Math.random() * motivations.length)];
    
    // Medication checkbox handling
    const medCheckboxes = document.querySelectorAll('.med-check');
    medCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const medId = this.getAttribute('data-id');
            const status = this.checked ? 1 : 0;
            
            fetch('api/update_med_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    med_id: medId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusSpan = document.querySelector(`.med-status[data-id="${medId}"]`);
                    if (statusSpan) {
                        statusSpan.textContent = status ? '✓ Pris' : '';
                        statusSpan.className = status ? 'med-status taken' : 'med-status';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    
    // Emotion form handling
    const emotionForm = document.querySelector('.emotion-form');
    if (emotionForm) {
        emotionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emotion = document.getElementById('emotionSelect').value;
            const intensity = document.getElementById('intensitySelect').value;
            
            if (!emotion || !intensity) {
                alert('Veuillez sélectionner une émotion et une intensité.');
                return;
            }
            
            fetch('api/save_emotion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    emotion: emotion,
                    intensity: intensity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Émotion enregistrée avec succès !');
                    // Refresh the page to update the chart
                    location.reload();
                } else {
                    alert('Erreur lors de l\'enregistrement de l\'émotion.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue.');
            });
        });
    }
    
    // Chatbot functionality
    const assistantInput = document.getElementById('assistantInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const assistantMessages = document.getElementById('assistantMessages');
    
    if (assistantInput && sendMessageBtn && assistantMessages) {
        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(isUser ? 'user-message' : 'bot-message');
            messageDiv.textContent = message;
            assistantMessages.appendChild(messageDiv);
            assistantMessages.scrollTop = assistantMessages.scrollHeight;
        }
        
        function sendMessage() {
            const message = assistantInput.value.trim();
            if (!message) return;
            
            addMessage(message, true);
            assistantInput.value = '';
            
            // Simulate bot response (replace with actual API call)
            setTimeout(() => {
                // This is a simple simulation - replace with actual API
                const responses = [
                    "Je suis désolé, je ne suis qu'une simulation pour l'instant.",
                    "N'oubliez pas de prendre vos médicaments aujourd'hui.",
                    "Pour des questions médicales spécifiques, consultez toujours votre médecin.",
                    "Rester hydraté est important pour votre santé !",
                    "Avez-vous fait de l'exercice aujourd'hui ?"
                ];
                const botResponse = responses[Math.floor(Math.random() * responses.length)];
                addMessage(botResponse);
            }, 1000);
        }
        
        sendMessageBtn.addEventListener('click', sendMessage);
        assistantInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
});
</script>
<style>
    /* Profile photo styles */
.profile-container {
    display: flex;
    align-items: center;
    gap: 20px;
}

.profile-photo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--gray);
    border: 3px solid var(--primary-light);
}

.greeting-text {
    flex: 1;
}

/* Side by side layout for emotions and chatbot */
.side-by-side-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    grid-column: span 12;
}

@media (max-width: 768px) {
    .side-by-side-container {
        grid-template-columns: 1fr;
    }
}

/* Enhanced emotion card */
.emotions-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Enhanced chatbot card */
.chatbot-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.chat-container {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 300px;
    max-height: 300px;
}

.message {
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 18px;
    margin-bottom: 5px;
    word-break: break-word;
}

.bot-message {
    align-self: flex-start;
    background-color: var(--light-gray);
    color: var(--secondary);
    border-bottom-left-radius: 5px;
}

.user-message {
    align-self: flex-end;
    background-color: var(--primary-light);
    color: var(--dark);
    border-bottom-right-radius: 5px;
}

.chat-input {
    display: flex;
    margin-top: 10px;
    border-radius: 25px;
    border: 1px solid var(--light-gray);
    overflow: hidden;
    background-color: #fff;
}

.chat-input input {
    flex: 1;
    padding: 10px 15px;
    border: none;
    outline: none;
}

.chat-input button {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.chat-input button:hover {
    background-color: var(--primary-dark);
}

/* Medication and appointment cards styles */
.med-status.taken {
    color: var(--success);
    font-weight: bold;
    margin-left: 10px;
}

.empty-state {
    text-align: center;
    padding: 20px;
    color: var(--gray);
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 10px;
}
</style>
