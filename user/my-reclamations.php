<?php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/../php/controllers/UserController.php';

$page_title  = "Mes réclamations";
$active_menu = "list";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Utilisateur';
$userId = $_SESSION['user_id'];

$statusFilter = $_GET['filter'] ?? 'all';

$userController = new UserController();
$reclamations = $userController->getAllReclamations($userId, $statusFilter);

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

function getPriorityBadge($priorite) {
    switch($priorite) {
        case 'urgente': return 'priority-urgent';
        case 'haute': return 'priority-high';
        case 'moyenne': return 'priority-medium';
        case 'basse': return 'priority-low';
        default: return 'priority-medium';
    }
}

function getPriorityText($priorite) {
    switch($priorite) {
        case 'urgente': return 'Urgente';
        case 'haute': return 'Haute';
        case 'moyenne': return 'Moyenne';
        case 'basse': return 'Basse';
        default: return ucfirst($priorite);
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
            <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar">
        </div>
    </header>

    <section class="content">
        <h1>Mes réclamations</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <section class="section">
            <div class="section-header">
                <div class="filter-buttons">
                    <a href="?filter=all" class="filter-btn <?= $statusFilter === 'all' ? 'active' : '' ?>">
                        <i class="fas fa-list"></i> Toutes
                    </a>
                    <a href="?filter=non_assignee" class="filter-btn <?= $statusFilter === 'non_assignee' ? 'active' : '' ?>">
                        <i class="fas fa-clock"></i> En attente
                    </a>
                    <a href="?filter=en_cours" class="filter-btn <?= $statusFilter === 'en_cours' ? 'active' : '' ?>">
                        <i class="fas fa-hourglass-half"></i> En cours
                    </a>
                    <a href="?filter=resolue" class="filter-btn <?= $statusFilter === 'resolue' ? 'active' : '' ?>">
                        <i class="fas fa-check-circle"></i> Résolues
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reclamations)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">Aucune réclamation trouvée</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($reclamations as $rec): ?>
                                <tr>
                                    <td><?= formatReclamationId($rec['id']) ?></td>
                                    <td><?= htmlspecialchars($rec['objet']) ?></td>
                                    <td><?= htmlspecialchars($rec['categorie'] ?? 'Non définie') ?></td>
                                    <td><?= date('d/m/Y', strtotime($rec['date_creation'])) ?></td>
                                    <td><span class="priority-badge <?= getPriorityBadge($rec['priorite']) ?>"><?= getPriorityText($rec['priorite']) ?></span></td>
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
