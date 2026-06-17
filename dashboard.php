<?php
session_start();

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';
/**
 * Pitwall - Page d'accueil
 * Affiche les capteurs (Température, Luminosité, Humidité)
 * et un bouton "Lancer test" piloté par un capteur de proximité + buzzer
 */

// Récupération des données capteurs depuis la table G2B
function getSensorData() {
    global $bdd;

    try {
        // Récupérer les dernières données de la table G2B
        $stmt = $bdd->query('SELECT temperature, humidite, date FROM G2B ORDER BY date DESC LIMIT 1');
        $data = $stmt->fetch();

        if ($data) {
            return [
                'temperature' => floatval($data['temperature']),   // °C
                'humidite'    => floatval($data['humidite']),      // %
                'luminosite'  => "__",                              // Non disponible dans G2B
                'proximite'   => false,                             // À adapter selon vos besoins
                'date'        => $data['date']                      // Date de la mesure
            ];
        } else {
            // Aucune donnée disponible
            return [
                'temperature' => "--",
                'humidite'    => "--",
                'luminosite'  => "--",
                'proximite'   => false,
                'date'        => null
            ];
        }
    } catch (PDOException $e) {
        // Erreur de connexion
        error_log('Erreur getSensorData: ' . $e->getMessage());
        return [
            'temperature' => "ERR",
            'humidite'    => "ERR",
            'luminosite'  => "ERR",
            'proximite'   => false,
            'date'        => null
        ];
    }
}

// Initialiser avec des valeurs par défaut (__) au lieu de charger la base de données immédiatement
$data = [
    'temperature' => "__",
    'humidite'    => "__",
    'luminosite'  => "__",
    'proximite'   => false,
    'date'        => null
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pitwall - Accueil</title>
    <script type="module" src="vendor/model-viewer.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<?php include __DIR__ . '/navbar.html'; ?>

<!-- Modèle 3D en fond (GLB) : auto-rotation -->
<model-viewer id="bgModel"
    src="res/amg_f1_w15_2024__www.vecarz.com.glb"
    alt="Modèle Mercedes-AMG F1"
    auto-rotate
    auto-rotate-delay="0"
    rotation-per-second="0.12"
    camera-orbit="0deg 75deg 8m"
    field-of-view="40deg"
    ar-modes="webxr scene-viewer quick-look"
    exposure="1"
    shadow-intensity="0.5"
    style="pointer-events:none;">
</model-viewer>

<br>
<br>


<div class="pitwall-board ui-block">

    <!-- Ligne du haut : Température / Luminosité -->
    <div class="sensors-row">
        <div class="sensor-card">
            <h3>Température</h3>
            <div class="value">
                <?php echo htmlspecialchars($data['temperature']); ?><span class="unit">°C</span>
            </div>
        </div>

        <div class="sensor-card">
            <h3>Luminosité</h3>
            <div class="value">
                <?php echo htmlspecialchars($data['luminosite']); ?><span class="unit">lux</span>
            </div>
        </div>
    </div>

    <!-- Ligne du milieu : Humidité + Bouton Lancer test -->
    <div class="sensors-row">
        <div class="sensor-card">
            <h3>Humidité</h3>
            <div class="value">
                <?php echo htmlspecialchars($data['humidite']); ?><span class="unit">%</span>
            </div>
        </div>

        <div class="test-zone" style="flex:2;">
            <button id="launchTest" class="test-button <?php echo $data['proximite'] ? '' : 'pulse'; ?>">
                Lancer test
            </button>
        </div>
    </div>

</div>


<footer>
    Pitwall &mdash; Mercedes-AMG Petronas F1 Team
</footer>



<!-- Chatbot UI -->
<?php include 'chatbot_ui.html'; ?>

<!-- Le modèle est en fond et strictement non-interactif (pointer-events:none). Aucune activation via UI. -->

<script>
    // Action au clic sur "Lancer test" — récupère les données capteurs avec délai de 3 secondes
    document.getElementById('launchTest').addEventListener('click', function () {
        this.disabled = true;
        this.textContent = 'Test en cours...';

        // Appel AJAX vers sensors.php pour récupérer les données capteurs
        fetch('sensors.php')
            .then(response => response.json())
            .then(data => {
                // Mise à jour des valeurs affichées
                document.querySelectorAll('.sensor-card .value')[0].innerHTML =
                    data.temperature + '<span class="unit">°C</span>';
                document.querySelectorAll('.sensor-card .value')[1].innerHTML =
                    data.luminosite + '<span class="unit">lux</span>';
                document.querySelectorAll('.sensor-card .value')[2].innerHTML =
                    data.humidite + '<span class="unit">%</span>';

                this.textContent = 'Test terminé';
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = 'Lancer test';
                }, 3000);
            })
            .catch(() => {
                this.textContent = 'Erreur';
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = 'Lancer test';
                }, 3000);
            });
    });
</script>

</body>
</html>