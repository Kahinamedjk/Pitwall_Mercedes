<?php
session_start();

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Circuits — Pitwall Mercedes
?>
<!doctype html>
<html lang="fr">
<head>
 
    <meta charset="UTF-8">
    <title>Pitwall Mercedes - Circuits</title>
    <link rel="stylesheet" href="assets/styles.css">
 
</head>
 
<body>
 
    <?php include 'navbar.html'; ?>
 
    <div class="ui-block">
 
        <header>
            <h1>CIRCUITS F1</h1>
            <p class="page-description">
                Découvrez les différents circuits du championnat du monde de Formule 1.
            </p>
        </header>
 
        <section class="circuits-board">
 
            <div class="circuit-card">
                <div class="circuit-summary">
                    <img src="res/ita_flag.webp" alt="Drapeau de l'Italie">
                    <div class="circuit-content">
                        <h2>Monza</h2>
                        <p>🇮🇹 Italie</p>
                    </div>
                    <button class="expand-toggle" aria-label="Explorer">▼</button>
                </div>
                <div class="circuit-details">
                    <img src="res/monza.jpeg" alt="Circuit de Monza" class="circuit-full-img">
                    <div class="circuit-meta">
                        <p><span class="label">Longueur</span> 5.793 km</p>
                        <p><span class="label">Tours</span> 53</p>
                    </div>
                </div>
            </div>
 
            <div class="circuit-card">
                <div class="circuit-summary">
                    <img src="res/bel_flag.jpg" alt="Drapeau de la Belgique">
                    <div class="circuit-content">
                        <h2>Spa-Francorchamps</h2>
                        <p>🇧🇪 Belgique</p>
                    </div>
                    <button class="expand-toggle" aria-label="Explorer">▼</button>
                </div>
                <div class="circuit-details">
                    <img src="res/spa.jpeg" alt="Circuit de Spa-Francorchamps" class="circuit-full-img">
                    <div class="circuit-meta">
                        <p><span class="label">Longueur</span> 7.004 km</p>
                        <p><span class="label">Tours</span> 44</p>
                    </div>
                </div>
            </div>
 
            <div class="circuit-card">
                <div class="circuit-summary">
                    <img src="res/uk_flag.jpg" alt="Drapeau du Royaume-Uni">
                    <div class="circuit-content">
                        <h2>Silverstone</h2>
                        <p>🇬🇧 Royaume-Uni</p>
                    </div>
                    <button class="expand-toggle" aria-label="Explorer">▼</button>
                </div>
                <div class="circuit-details">
                    <img src="res/silverstone.jpeg" alt="Circuit de Silverstone" class="circuit-full-img">
                    <div class="circuit-meta">
                        <p><span class="label">Longueur</span> 5.891 km</p>
                        <p><span class="label">Tours</span> 52</p>
                    </div>
                </div>
            </div>
 
            <div class="circuit-card">
                <div class="circuit-summary">
                    <img src="res/usa_flag.png" alt="Drapeau des États-Unis">
                    <div class="circuit-content">
                        <h2>Los Angeles Strip</h2>
                        <p>🇺🇸 États-Unis</p>
                    </div>
                    <button class="expand-toggle" aria-label="Explorer">▼</button>
                </div>
                <div class="circuit-details">
                    <img src="res/LA.jpeg" alt="Circuit de Los Angeles Strip" class="circuit-full-img">
                    <div class="circuit-meta">
                        <p><span class="label">Longueur</span> 6.201 km</p>
                        <p><span class="label">Tours</span> 50</p>
                    </div>
                </div>
            </div>
 
            <div class="circuit-card">
                <div class="circuit-summary">
                    <img src="res/uae_flag.jpg" alt="Drapeau des Émirats Arabes Unis">
                    <div class="circuit-content">
                        <h2>Yas Marina</h2>
                        <p>🇦🇪 Abu Dhabi</p>
                    </div>
                    <button class="expand-toggle" aria-label="Explorer">▼</button>
                </div>
                <div class="circuit-details">
                    <img src="res/yas.jpeg" alt="Circuit de Yas Marina" class="circuit-full-img">
                    <div class="circuit-meta">
                        <p><span class="label">Longueur</span> 5.281 km</p>
                        <p><span class="label">Tours</span> 58</p>
                    </div>
                </div>
            </div>
 
            <div class="circuit-card">
                <div class="circuit-summary">
                    <img src="res/qat_flag.jpg" alt="Drapeau du Qatar">
                    <div class="circuit-content">
                        <h2>Lusail</h2>
                        <p>🇶🇦 Qatar</p>
                    </div>
                    <button class="expand-toggle" aria-label="Explorer">▼</button>
                </div>
                <div class="circuit-details">
                    <img src="res/lusail.jpeg" alt="Circuit de Lusail" class="circuit-full-img">
                    <div class="circuit-meta">
                        <p><span class="label">Longueur</span> 5.419 km</p>
                        <p><span class="label">Tours</span> 57</p>
                    </div>
                </div>
            </div>
 
        </section>
 
    </div>
 
    <footer>© <?php echo date('Y'); ?> Pitwall Mercedes</footer>
 
    <!-- Chatbot UI -->
    <?php include 'chatbot_ui.html'; ?>


    <script>
        document.querySelectorAll('.circuit-card').forEach(card => {
            const toggle = card.querySelector('.expand-toggle');
            toggle.addEventListener('click', () => {
                card.classList.toggle('expanded');
                toggle.textContent = card.classList.contains('expanded') ? '▲' : '▼';
            });
        });
    </script>
 
</body>
</html>