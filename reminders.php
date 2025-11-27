<?php
session_start();
require_once 'config\bd.php';

// Vérifier les médicaments à prendre maintenant
$now = date('H:i');
$stmt = $conn->prepare("SELECT * FROM medicaments WHERE user_id = ? AND TIME(heure) BETWEEN ? AND ?");
$stmt->execute([$_SESSION['user_id'], $now, date('H:i', strtotime('+5 minutes'))]);
$medicaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier les rendez-vous aujourd'hui
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT * FROM rendezvous WHERE user_id = ? AND date = ? AND TIME(heure) BETWEEN ? AND ?");
$stmt->execute([$_SESSION['user_id'], $today, $now, date('H:i', strtotime('+30 minutes'))]);
$rendezvous = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<h2>Rappels</h2>

<div class="reminders-container">
    <?php if (!empty($medicaments)): ?>
    <div class="reminder-section">
        <h3><i class="fas fa-pills"></i> Médicaments à prendre maintenant</h3>
        <ul>
            <?php foreach ($medicaments as $med): ?>
            <li>
                <?= htmlspecialchars($med['nom']) ?> - 
                <?= htmlspecialchars($med['dose']) ?> - 
                <?= date('H:i', strtotime($med['heure'])) ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($rendezvous)): ?>
    <div class="reminder-section">
        <h3><i class="fas fa-calendar-alt"></i> Rendez-vous prochains</h3>
        <ul>
            <?php foreach ($rendezvous as $rdv): ?>
            <li>
                <?= htmlspecialchars($rdv['patient_name']) ?> - 
                <?= htmlspecialchars($rdv['medecin_name']) ?> - 
                <?= date('H:i', strtotime($rdv['heure'])) ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if (empty($medicaments) && empty($rendezvous)): ?>
    <p>Aucun rappel pour le moment.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>