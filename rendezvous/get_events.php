<?php
session_start();
require_once '../config/bd.php';

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, medecin_name, specialite, date, heure FROM rendezvous WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $rendezvous = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];
    foreach ($rendezvous as $rdv) {
        $start = $rdv['date'] . 'T' . $rdv['heure'];
        $events[] = [
            'id' => $rdv['id'],
            'title' => $rdv['medecin_name'] . ' (' . $rdv['specialite'] . ')',
            'start' => $start
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($events);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Erreur lors de la récupération des événements']);
}
?>