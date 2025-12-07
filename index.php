<?php
session_start();

$role = $_SESSION['user_role'] ?? null;

// Detect base path (works if the app is deployed in a subfolder)
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath === '/') {
    $basePath = '';
}

// Pick the first existing dashboard target for the current role
$dashboardCandidates = [
    'admin' => ['/admin/admin-dashboard.php'],
    'agent' => ['/agent/agent-dashboard.php', '/agent/agent-dashboard.html'],
    'user'  => ['/user/user-dashboard.php', '/user/user-dashboard.html'],
];

$dashboardUrl = null;
if ($role && isset($dashboardCandidates[$role])) {
    foreach ($dashboardCandidates[$role] as $candidate) {
        $fsPath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $candidate);
        if (file_exists($fsPath)) {
            $dashboardUrl = $basePath . $candidate;
            break;
        }
    }
}

$loginUrl    = $basePath . '/auth/login.php';
$registerUrl = $basePath . '/auth/register.php';
$logoutUrl   = $basePath . '/auth/logout.php';
$homeUrl     = $basePath === '' ? '/' : $basePath . '/';

// If already logged in, send the user to their dashboard
if ($dashboardUrl) {
    header('Location: ' . $dashboardUrl);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations - Accueil</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <i class="fas fa-file-alt"></i>
                <span>ReclamPro</span>
            </div>
            <nav class="nav-menu">
                <a href="<?= htmlspecialchars($homeUrl) ?>" class="nav-link active">Accueil</a>
                <a href="#features" class="nav-link">Fonctionnalités</a>
                <?php if ($dashboardUrl): ?>
                    <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="nav-link">Tableau de bord</a>
                    <a href="<?= htmlspecialchars($logoutUrl) ?>" class="nav-link">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($loginUrl) ?>" class="nav-link">Connexion</a>
                    <a href="<?= htmlspecialchars($registerUrl) ?>" class="nav-link btn-primary">S'inscrire</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Gestion Simplifiée des Réclamations</h1>
                <p>Suivez, gérez et résolvez vos réclamations facilement et rapidement.</p>
                <div class="hero-buttons">
                    <?php if ($dashboardUrl): ?>
                        <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="btn btn-lg btn-primary">Accéder à mon tableau de bord</a>
                        <a href="<?= htmlspecialchars($logoutUrl) ?>" class="btn btn-lg btn-secondary">Se déconnecter</a>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($registerUrl) ?>" class="btn btn-lg btn-primary">Démarrer maintenant</a>
                        <a href="<?= htmlspecialchars($loginUrl) ?>" class="btn btn-lg btn-secondary">Déjà inscrit ? Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <i class="fas fa-tasks"></i>
            </div>
        </section>

        <section class="features" id="features">
            <h2>Nos Fonctionnalités</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-pencil-alt"></i>
                    <h3>Soumettre une Réclamation</h3>
                    <p>Créez et soumettez vos réclamations facilement avec tous les détails nécessaires.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-eye"></i>
                    <h3>Suivi en Temps Réel</h3>
                    <p>Suivez l'état de vos réclamations à chaque étape du processus.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-comments"></i>
                    <h3>Communication Directe</h3>
                    <p>Communiquez avec nos agents pour clarifier ou obtenir des mises à jour.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Gestion Avancée</h3>
                    <p>Les agents et administrateurs gèrent efficacement toutes les réclamations.</p>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="cta-content">
                <h2>Prêt à commencer ?</h2>
                <p>Rejoignez nos utilisateurs satisfaits et gérez vos réclamations efficacement.</p>
                <?php if ($dashboardUrl): ?>
                    <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="btn btn-lg btn-primary">Ouvrir mon espace</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($registerUrl) ?>" class="btn btn-lg btn-primary">S'inscrire maintenant</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2025 ReclamPro. Tous droits réservés.</p>
            <div class="footer-links">
                <a href="#">Politique de confidentialité</a>
                <a href="#">Conditions d'utilisation</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>
