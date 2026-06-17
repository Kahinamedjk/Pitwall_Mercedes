<?php
// sensors.php - API pour récupérer les données capteurs en JSON
include 'config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Récupérer les dernières données de la table G2B
    $stmt = $bdd->query('SELECT temperature, humidite, date FROM G2B ORDER BY date DESC LIMIT 1');
    $data = $stmt->fetch();

    if ($data) {
        echo json_encode([
            'temperature' => floatval($data['temperature']),
            'humidite'    => floatval($data['humidite']),
            'luminosite'  => '__',
            'proximite'   => false,
            'date'        => $data['date']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Aucune donnée disponible']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    error_log('Erreur sensors.php: ' . $e->getMessage());
    echo json_encode(['error' => 'Erreur de connexion à la base de données']);
}
?>

