<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

// Validate user session (you might already have session validation logic)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Utilisateur non authentifié']);
    exit;
}

// Parse and validate input
$data = json_decode(file_get_contents('php://input'), true);
$message = trim($data['message'] ?? '');

if (!$message || strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message vide ou trop long (max 1000 caractères)']);
    exit;
}

try {
    $payload = [

    // Initialize cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Execute the cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError || $httpCode !== 200) {
        http_response_code(502);
        exit;
    }

    $result = json_decode($response, true);

    // Check if the response contains the model's output

    if (!$botResponse) {
        http_response_code(502);
        echo json_encode(['success' => false, 'error' => 'Réponse invalide de l\'API']);
        exit;
    }

    // Return the bot's response to the frontend
    echo json_encode(['success' => true, 'response' => $botResponse]);

} catch (Exception $e) {
    // Log error and return response
    error_log("Chatbot error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
}
?>
