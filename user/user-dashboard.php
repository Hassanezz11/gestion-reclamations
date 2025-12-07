<?php
session_start();
$page_title  = "Tableau de bord utilisateur";
$active_menu = "dashboard";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header("Location: /auth/login.php");
    exit;
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
            <img src="../assets/images/logo.png" alt="Avatar" class="avatar">
        </div>
    </header>

    <section class="content">
        <h1>Tableau de bord</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary-color);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Réclamations totales</div>
                    <div class="stat-value">8</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(234, 88, 12, 0.1); color: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En attente</div>
                    <div class="stat-value">2</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(8, 145, 178, 0.1); color: var(--info-color);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En cours</div>
                    <div class="stat-value">3</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(22, 163, 74, 0.1); color: var(--success-color);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Résolues</div>
                    <div class="stat-value">3</div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="section-header">
                <h2>Réclamations récentes</h2>
                <a href="my-reclamations.php" class="btn btn-primary">Voir tout</a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#REC-2025-001</td>
                            <td>Produit défectueux</td>
                            <td>01/11/2025</td>
                            <td><span class="badge badge-success">Résolue</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-002</td>
                            <td>Service insatisfaisant</td>
                            <td>02/11/2025</td>
                            <td><span class="badge badge-info">En cours</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-003</td>
                            <td>Livraison en retard</td>
                            <td>03/11/2025</td>
                            <td><span class="badge badge-warning">En attente</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/user-footer.php'; ?>
