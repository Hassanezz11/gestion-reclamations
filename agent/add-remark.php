<?php
session_start();
$page_title  = "Ajouter une remarque";
$active_menu = "reclamations";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'agent') {
    header("Location: /auth/login.php");
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Agent';

include __DIR__ . '/includes/agent-header.php';
?>

<div class="layout">
<?php include __DIR__ . '/includes/agent-sidebar.php'; ?>
<main class="main">

    <header class="top-bar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="top-bar-right">
            <div class="user-info">
                <span>Bonjour, <?= htmlspecialchars($userName) ?></span>
                <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar">
            </div>
        </div>
    </header>

    <section class="content">
        <div class="section">
            <div class="section-header">
                <h1>Ajouter une remarque interne</h1>
                <p class="topbar-subtitle">Ces remarques ne sont visibles que par les agents et l'administrateur.</p>
            </div>

            <form class="card form-card">
                <div class="form-group">
                    <label for="statut">Statut interne</label>
                    <select id="statut" name="statut">
                        <option>Client contacté</option>
                        <option>En attente de documents</option>
                        <option>Traitement en cours</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remarque">Remarque</label>
                    <textarea id="remarque" name="remarque" rows="4"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer la remarque</button>
                </div>
            </form>
        </div>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
