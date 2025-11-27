<?php
session_start();
require_once __DIR__ . '/../config/bd.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non authentifié']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$medicationId = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!is_numeric($medicationId) || !in_array($status, [0, 1])) {
    echo json_encode(['success' => false, 'error' => 'Données invalides: ID ou statut incorrect']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE medicaments SET is_taken = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([(int)$status, (int)$medicationId, $_SESSION['user_id']]);
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'error' => 'Aucun médicament trouvé pour cet ID ou utilisateur']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Mise à jour réussie']);
    }
} catch (PDOException $e) {
    error_log("Erreur mise à jour médicament: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erreur base de données: ' . $e->getMessage()]);
}
?>
<style>
.med-status {
    font-size: 0.9rem;
    color: var(--primary);
    min-width: 100px;
    text-align: right;
}
.med-status.error {
    color: var(--danger);
}
</style>