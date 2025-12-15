<?php
require_once __DIR__ . '/../php/auth.php';
Auth::requireRole('agent');

$page_title  = "Gerer les reclamations";
$active_menu = "reclamations";

$userName = $_SESSION['user_name'] ?? 'Agent';
$agentId  = (int)($_SESSION['user_id'] ?? 0);

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Reclamation.php';

$pdo = Database::getInstance();
$reclamationModel = new Reclamation($pdo);

$search = trim($_GET['q'] ?? '');
$statusFilter = $_GET['status'] ?? 'all';
$statusValue = $statusFilter === 'all' ? null : $statusFilter;

$reclamations = $reclamationModel->getForAgent($agentId, $statusValue, $search);

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
        <h1>Gestion des reclamations</h1>

        <section class="section">
            <div class="section-header">
                <form class="search-bar" method="get" action="manage-reclamations.php">
                    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par client ou objet">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
                    <button class="btn btn-secondary btn-icon" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="filter-buttons">
                    <?php
                        $filters = [
                            'all' => 'Toutes',
                            'non_assignee' => 'En attente',
                            'en_cours' => 'En cours',
                            'resolue' => 'Resolues'
                        ];
                    ?>
                    <?php foreach ($filters as $key => $label): ?>
                        <a href="manage-reclamations.php?status=<?= urlencode($key) ?>&q=<?= urlencode($search) ?>"
                           class="filter-btn <?= $statusFilter === $key ? 'active' : '' ?>">
                            <?= htmlspecialchars($label) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
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
                        <?php if (empty($reclamations)): ?>
                            <tr><td colspan="7" style="text-align:center;">Aucune reclamation trouvee.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reclamations as $rec): ?>
                                <tr>
                                    <td>#REC-<?= htmlspecialchars(str_pad((string)$rec['id'], 4, '0', STR_PAD_LEFT)) ?></td>
                                    <td><?= htmlspecialchars($rec['client']) ?></td>
                                    <td><?= htmlspecialchars($rec['categorie'] ?? 'Non defini') ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($rec['date_creation']))) ?></td>
                                    <td><?= priorityBadge($rec['priorite']) ?></td>
                                    <td><?= statusBadge($rec['statut']) ?></td>
                                    <td>
                                        <a href="reclamation-agent-details.php?id=<?= urlencode($rec['id']) ?>" class="btn btn-sm btn-secondary">Details</a>
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
