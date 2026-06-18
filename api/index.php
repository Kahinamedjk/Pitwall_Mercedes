<?php
/**
 * api/index.php - Routeur Vercel pour Pitwall Mercedes
 * Redirige les requêtes vers les fichiers PHP à la racine
 */

// Changer le répertoire de travail vers la racine du projet
chdir(__DIR__ . '/..');

// Récupérer l'URI propre
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Éviter que le routeur ne s'appelle lui-même
if (preg_match('#^/api/index\.php#', $uri)) {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

// Si c'est un fichier statique existant, on laisse Vercel le servir
$staticPath = __DIR__ . '/..' . $uri;
if (!empty($uri) && preg_match('/\.(css|js|png|jpg|jpeg|webp|gif|svg|ico|glb|json)$/', $uri)) {
    if (file_exists($staticPath)) {
        return false;
    }
}

// Carte des routes : URI → fichier PHP
$routes = [
    '/'                => 'index.php',
    '/index'           => 'index.php',
    '/dashboard'       => 'dashboard.php',
    '/login'           => 'login.php',
    '/register'        => 'register.php',
    '/logout'          => 'logout.php',
    '/circuit'         => 'circuit.php',
    '/circuits'        => 'circuit.php',
    '/sensors'         => 'sensors.php',
    '/chatbot'         => 'chatbot.php',
    '/faq'             => 'faq.php',
    '/modele-f1'       => 'modele-f1.php',
    '/performances'    => 'performances.php',
    '/profil'          => 'profil.php',
    '/test'            => 'test.php',
    '/api/faq_list'    => 'api/faq_list.php',
    '/api/faq_submit'  => 'api/faq_submit.php',
];

// Vérifier si la route existe
if (isset($routes[$uri])) {
    $file = __DIR__ . '/../' . $routes[$uri];
    if (file_exists($file) && realpath($file) !== __FILE__) {
        require $file;
        return;
    }
}

// Route avec nettoyage d'URL : /page → /page.php
$page = ltrim($uri, '/');
$phpFile = __DIR__ . '/../' . $page . '.php';
if ($page !== '' && file_exists($phpFile) && realpath($phpFile) !== __FILE__) {
    require $phpFile;
    return;
}

// 404
http_response_code(404);
?><!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>404 - Pitwall Mercedes</title>
<link rel="stylesheet" href="/assets/styles.css"></head><body>
<?php include 'navbar.html'; ?>
<main class="ui-block" style="text-align:center;padding:80px 20px;">
<h1 style="font-size:4rem;color:var(--petronas-teal);">404</h1>
<p style="color:#aaa;font-size:1.2rem;">Page non trouvée</p>
<a href="/" class="connexion-button" style="display:inline-block;margin-top:30px;text-decoration:none;padding:15px 40px;">Retour à l'accueil</a>
</main></body></html>
