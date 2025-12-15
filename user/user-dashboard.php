<?php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/../php/controllers/UserController.php';

$page_title  = "Tableau de bord utilisateur";
$active_menu = "dashboard";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Utilisateur';
$userId = $_SESSION['user_id'];

$userController = new UserController();
$stats = $userController->getDashboardStats($userId);
$recentReclamations = $userController->getRecentReclamations($userId, 5);

function getStatusBadge($statut) {
    switch($statut) {
        case 'resolue': return 'badge-success';
        case 'en_cours': return 'badge-info';
        case 'non_assignee': return 'badge-warning';
        default: return 'badge-secondary';
    }
}

function getStatusText($statut) {
    switch($statut) {
        case 'resolue': return 'Résolue';
        case 'en_cours': return 'En cours';
        case 'non_assignee': return 'En attente';
        default: return $statut;
    }
}

function formatReclamationId($id) {
    return '#REC-' . date('Y') . '-' . str_pad($id, 3, '0', STR_PAD_LEFT);
}

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
                    <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(234, 88, 12, 0.1); color: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En attente</div>
                    <div class="stat-value"><?= $stats['en_attente'] ?? 0 ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(8, 145, 178, 0.1); color: var(--info-color);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En cours</div>
                    <div class="stat-value"><?= $stats['en_cours'] ?? 0 ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(22, 163, 74, 0.1); color: var(--success-color);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Résolues</div>
                    <div class="stat-value"><?= $stats['resolues'] ?? 0 ?></div>
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
                        <?php if (empty($recentReclamations)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">Aucune réclamation pour le moment</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($recentReclamations as $rec): ?>
                                <tr>
                                    <td><?= formatReclamationId($rec['id']) ?></td>
                                    <td><?= htmlspecialchars($rec['categorie'] ?? 'Non définie') ?></td>
                                    <td><?= date('d/m/Y', strtotime($rec['date_creation'])) ?></td>
                                    <td><span class="badge <?= getStatusBadge($rec['statut']) ?>"><?= getStatusText($rec['statut']) ?></span></td>
                                    <td>
                                        <a href="reclamation-details.php?id=<?= $rec['id'] ?>" class="btn btn-sm btn-secondary">Détails</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/user-footer.php'; ?>
