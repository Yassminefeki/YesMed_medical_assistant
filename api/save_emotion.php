<?php
session_start();
require_once __DIR__ . '/../config/bd.php';

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $conn->prepare("INSERT INTO emotions (user_id, emotion, intensity, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $data['emotion'],
        $data['intensity'],
        $data['notes'] ?? null
    ]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}