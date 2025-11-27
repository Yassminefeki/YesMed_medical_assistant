<?php
require_once __DIR__ . '/config/bd.php';

date_default_timezone_set('Africa/Tunis'); // set your correct timezone

// Get current time + 15 minutes
$targetTime = (new DateTime())->add(new DateInterval('PT15M'))->format('H:i');

// Get today's date
$today = date('Y-m-d');

try {
    $stmt = $conn->prepare("
        SELECT u.email, u.nom, m.id, m.nom AS med_name, m.dose, m.heure 
        FROM medicaments m
        JOIN users u ON m.user_id = u.id
        WHERE DATE(m.created_at) = ? 
          AND m.heure = ? 
          AND m.is_taken = 0
    ");
    
    $stmt->execute([$today, $targetTime]);
    $reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reminders as $reminder) {
        $to = $reminder['email'];
        $subject = "💊 Rappel Médicament - YesMed";
        $message = "Bonjour " . $reminder['nom'] . ",\n\n"
                 . "Ceci est un rappel pour prendre votre médicament **" 
                 . $reminder['med_name'] . "** (Dose: " . $reminder['dose'] . ") à " . $reminder['heure'] . ".\n\n"
                 . "Merci d'utiliser YesMed !";
        $headers = "From: no-reply@yesmed.com";

        // Send email (you can change to push notification or SMS)
        mail($to, $subject, $message, $headers);
    }

    echo json_encode(['success' => true, 'sent' => count($reminders)]);

} catch (PDOException $e) {
    error_log("Reminder send error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
}