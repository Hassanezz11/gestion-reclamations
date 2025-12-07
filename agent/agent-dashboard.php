<?php
session_start();
$page_title  = "Tableau de bord Agent";
$active_menu = "dashboard";

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
        <h1>Tableau de bord Agent</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary-color);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Réclamations assignées</div>
                    <div class="stat-value">12</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(234, 88, 12, 0.1); color: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En attente</div>
                    <div class="stat-value">4</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(8, 145, 178, 0.1); color: var(--info-color);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En cours</div>
                    <div class="stat-value">5</div>
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
                <h2>Réclamations à traiter</h2>
                <a href="manage-reclamations.php" class="btn btn-primary">Voir tout</a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#REC-2025-005</td>
                            <td>Sophie Martin</td>
                            <td>Service</td>
                            <td>01/11/2025</td>
                            <td><span class="priority-badge priority-urgent">Urgente</span></td>
                            <td><span class="badge badge-warning">En attente</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Traiter</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-006</td>
                            <td>Paul Moreau</td>
                            <td>Livraison</td>
                            <td>02/11/2025</td>
                            <td><span class="priority-badge priority-high">Haute</span></td>
                            <td><span class="badge badge-info">En cours</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-007</td>
                            <td>Claire Duval</td>
                            <td>Produit</td>
                            <td>03/11/2025</td>
                            <td><span class="priority-badge priority-medium">Moyenne</span></td>
                            <td><span class="badge badge-warning">En attente</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Traiter</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
