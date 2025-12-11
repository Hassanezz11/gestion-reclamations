<?php
session_start();
require_once __DIR__ . '/../php/config.php';
$page_title  = "Envoyer un message";
$active_menu = "list";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Utilisateur';

include __DIR__ . '/includes/user-header.php';
?>

<div class="layout">
<?php include __DIR__ . '/includes/user-sidebar.php'; ?>
<main class="main">

    <header class="topbar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-user">
            <span>Bonjour, <?= htmlspecialchars($userName) ?></span>
            <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar">
        </div>
    </header>

    <section class="content">
        <div class="section">
            <div class="section-header">
                <h1>Envoyer un message</h1>
                <p class="topbar-subtitle">Communiquez avec l'agent en charge de votre réclamation.</p>
            </div>

            <form class="card form-card">
                <div class="form-group">
                    <label for="message">Votre message</label>
                    <textarea id="message" name="message" rows="5"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/user-footer.php'; ?>
