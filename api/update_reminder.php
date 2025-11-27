<?php
require_once __DIR__ . '/../config/bd.php';

header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupérer les rappels
    try {
        $stmt = $conn->prepare("
            SELECT m.id, m.nom as title, m.dose as description, 
                   m.heure as time, m.is_taken, 'medication' as type, 'fa-pills' as icon
            FROM medicaments m
            WHERE m.user_id = ? AND DATE(m.created_at) = CURDATE()
            
            UNION
            
            SELECT r.id, CONCAT('RDV: ', r.specialite) as title, 
                   CONCAT('Dr. ', r.medecin_name) as description,
                   CONCAT(r.date, ' ', r.heure) as time, 0 as is_taken, 
                   'appointment' as type, 'fa-calendar' as icon
            FROM rendezvous r
            WHERE r.user_id = ? AND r.date = CURDATE()
        ");
        
        $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
        $reminders = $stmt->fetchAll();
        
        echo json_encode($reminders);
    } catch (PDOException $e) {
        error_log("Fetch reminders error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Erreur de base de données']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mettre à jour un rappel
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['id']) && isset($data['is_taken'])) {
        try {
            $stmt = $conn->prepare("UPDATE medicaments SET is_taken = ? WHERE id = ? AND user_id = ?");
            $success = $stmt->execute([$data['is_taken'], $data['id'], $_SESSION['user_id']]);
            
            echo json_encode(['success' => $success]);
        } catch (PDOException $e) {
            error_log("Update reminder error: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Erreur de base de données']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
}