<?php
// chatbot.php - API Backend pour Mistral AI Chatbot
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer les requêtes CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Lire les données JSON reçues
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Message requis']);
    exit;
}

// Inclure la configuration
require_once 'config.php';

// Historique de conversation (optionnel)
$messages = isset($input['history']) ? $input['history'] : [];
$messages[] = [
    'role' => 'user',
    'content' => $input['message']
];

// Appel à l'API Mistral
$ch = curl_init(MISTRAL_API_URL);

$payload = [
    'model' => MISTRAL_MODEL,
    'messages' => $messages,
    'temperature' => 0.7,
    'max_tokens' => 500,
];

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . MISTRAL_API_KEY,
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

// curl_close() est automatique depuis PHP 8.0

if ($error) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur API: ' . $error]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode !== 200) {
    http_response_code($httpCode);
    echo json_encode(['error' => $data['error']['message'] ?? 'Erreur inconnue']);
    exit;
}

// Extraire la réponse
$reply = $data['choices'][0]['message']['content'] ?? 'Désolé, je n\'ai pas compris.';
$history = $data['choices'][0]['message'] ?? [];
$history['role'] = 'assistant';
$history['content'] = $reply;

echo json_encode([
    'success' => true,
    'reply' => $reply,
    'history' => [$history]
]);
?>