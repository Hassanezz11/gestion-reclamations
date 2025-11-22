<?php
session_start();

// Simple front controller / redirector
// $role = $_SESSION['user_role'] ?? null;

// if ($role === 'admin') {
//     header('Location: /admin/admin-dashboard.php');
//     exit;
// } elseif ($role === 'agent') {
//     header('Location: /agent/agent-dashboard.php');
//     exit;
// } elseif ($role === 'user') {
//     header('Location: /user/dashboard.php');
//     exit;
// } else {
//     header('Location: /auth/login.php');
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réclamations - Accueil</title>
    <link rel="stylesheet" href="assets/css/main.css">
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
                <a href="index.html" class="nav-link active">Accueil</a>
                <a href="about.html" class="nav-link">À propos</a>
                <a href="login.html" class="nav-link">Connexion</a>
                <a href="register.html" class="nav-link btn-primary">S'inscrire</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Gestion Simplifiée des Réclamations</h1>
                <p>Suivez, gérez et résolvez vos réclamations facilement et rapidement</p>
                <div class="hero-buttons">
                    <a href="register.html" class="btn btn-lg btn-primary">Démarrer maintenant</a>
                    <a href="about.html" class="btn btn-lg btn-secondary">En savoir plus</a>
                </div>
            </div>
            <div class="hero-image">
                <i class="fas fa-tasks"></i>
            </div>
        </section>

        <section class="features">
            <h2>Nos Fonctionnalités</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-pencil-alt"></i>
                    <h3>Soumettre une Réclamation</h3>
                    <p>Créez et soumettez vos réclamations facilement avec tous les détails nécessaires</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-eye"></i>
                    <h3>Suivi en Temps Réel</h3>
                    <p>Suivez l'état de vos réclamations à chaque étape du processus</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-comments"></i>
                    <h3>Communication Directe</h3>
                    <p>Communiquez avec nos agents pour clarifier ou obtenir des mises à jour</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Gestion Avancée</h3>
                    <p>Les agents et administrateurs gèrent efficacement toutes les réclamations</p>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="cta-content">
                <h2>Prêt à commencer?</h2>
                <p>Rejoignez nos utilisateurs satisfaits et gérez vos réclamations efficacement</p>
                <a href="register.html" class="btn btn-lg btn-primary">S'inscrire maintenant</a>
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
