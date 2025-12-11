<?php
session_start();
require_once __DIR__ . '/../php/config.php';
$page_title  = "Tableau de bord Agent";
$active_menu = "dashboard";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'agent') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Agent';
$agentId  = (int)($_SESSION['user_id'] ?? 0);

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Reclamation.php';

$pdo = Database::getInstance();
$reclamationModel = new Reclamation($pdo);

$stats = $reclamationModel->countForAgentByStatus($agentId);
$recentReclamations = $reclamationModel->getForAgent($agentId, null, null, 5);

$stats += ['total' => $stats['total'] ?? array_sum($stats)];

function statusBadge(string $status): string {
    return match ($status) {
        'en_cours' => '<span class="badge badge-info">En cours</span>',
        'resolue' => '<span class="badge badge-success">Resolue</span>',
        default => '<span class="badge badge-warning">En attente</span>',
    };
}

function priorityBadge(string $priority): string {
    return match ($priority) {
        'haute' => '<span class="priority-badge priority-high">Haute</span>',
        'faible' => '<span class="priority-badge priority-low">Faible</span>',
        default => '<span class="priority-badge priority-medium">Moyenne</span>',
    };
}

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
                    <div class="stat-label">Reclamations assignees</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['total']) ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(234, 88, 12, 0.1); color: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En attente</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['non_assignee']) ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(8, 145, 178, 0.1); color: var(--info-color);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">En cours</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['en_cours']) ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(22, 163, 74, 0.1); color: var(--success-color);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Resolues</div>
                    <div class="stat-value"><?= htmlspecialchars($stats['resolue']) ?></div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="section-header">
                <h2>Reclamations a traiter</h2>
                <a href="manage-reclamations.php" class="btn btn-primary">Voir tout</a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Categorie</th>
                            <th>Date</th>
                            <th>Priorite</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentReclamations)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">Aucune reclamation assignee pour le moment.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentReclamations as $rec): ?>
                                <tr>
                                    <td>#REC-<?= htmlspecialchars(str_pad((string)$rec['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                                    <td><?= htmlspecialchars($rec['client']) ?></td>
                                    <td><?= htmlspecialchars($rec['categorie'] ?? 'Non defini') ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($rec['date_creation']))) ?></td>
                                    <td><?= priorityBadge($rec['priorite']) ?></td>
                                    <td><?= statusBadge($rec['statut']) ?></td>
                                    <td>
                                        <a href="reclamation-agent-details.php?id=<?= urlencode($rec['id']) ?>" class="btn btn-sm btn-secondary">Traiter</a>
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

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
