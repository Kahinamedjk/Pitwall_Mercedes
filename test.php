<?php
echo "Test de connexion Alwaydata\n";
echo "============================\n\n";

$db_host = 'mysql-pitwallg2.alwaydata.net';
$db_user = 'pitwallg2';
$db_password = 'Isepeleve';
$db_name = 'pitwallg2_capteurs';

echo "Host: " . $db_host . "\n";
echo "User: " . $db_user . "\n";
echo "DB: " . $db_name . "\n\n";

try {
    echo "Tentative de connexion...\n";
    $bdd = new PDO(
        'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',
        $db_user,
        $db_password
    );
    echo "✅ Connexion réussie!\n\n";

    // Tester les tables
    $tables = $bdd->query('SHOW TABLES')->fetchAll();
    echo "Tables disponibles:\n";
    foreach ($tables as $table) {
        echo "  - " . $table[0] . "\n";
    }

    // Tester la table G2B
    $count = $bdd->query('SELECT COUNT(*) as cnt FROM G2B')->fetch();
    echo "\nNombre d'enregistrements dans G2B: " . $count['cnt'] . "\n";

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Code erreur: " . $e->getCode() . "\n";
}
?>