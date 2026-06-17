<?php
// api/faq_submit.php - Soumettre une nouvelle question FAQ
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

require_once __DIR__ . '/../config.php';

$input = json_decode(file_get_contents('php://input'), true);
$question = trim($input['question'] ?? '');
$submitted_by = trim($input['submitted_by'] ?? '');

if (empty($question)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La question est requise']);
    exit;
}

if (strlen($question) > 500) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La question est trop longue (max 500 caractères)']);
    exit;
}

try {
    // Vérifier si la question existe déjà
    $check = $bdd->prepare('SELECT id FROM faq_g2b WHERE LOWER(question) = LOWER(?)');
    $check->execute([$question]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Cette question a déjà été posée.']);
        exit;
    }

    // Insérer la question (statut "pending" par défaut)
    $stmt = $bdd->prepare('INSERT INTO faq_g2b (question, submitted_by, status) VALUES (?, ?, "pending")');
    $stmt->execute([$question, $submitted_by ?: null]);

    echo json_encode([
        'success' => true,
        'message' => 'Merci ! Votre question a été soumise et sera traitée prochainement.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la soumission']);
}
?>