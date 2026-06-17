<?php
// api/faq_list.php - Récupérer les FAQ approuvées
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config.php';

try {
    $stmt = $bdd->query('SELECT id, question, answer, created_at FROM faq_g2b WHERE status = "approved" ORDER BY id ASC');
    $faqs = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'faqs' => $faqs
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur de récupération']);
}
?>