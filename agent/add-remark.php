<?php
require_once __DIR__ . '/../php/auth.php';
Auth::requireRole('agent');

$page_title  = "Ajouter une remarque";
$active_menu = "reclamations";

$userName = $_SESSION['user_name'] ?? 'Agent';
$agentId  = (int)($_SESSION['user_id'] ?? 0);
$reclamationId = (int)($_GET['id'] ?? 0);

if ($reclamationId <= 0) {
    header("Location: manage-reclamations.php");
    exit;
}

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Reclamation.php';
require_once __DIR__ . '/../php/models/Remark.php';

$pdo = Database::getInstance();
$reclamationModel = new Reclamation($pdo);
$remarkModel = new Remark($pdo);

$reclamation = $reclamationModel->getDetailsForAgent($reclamationId, $agentId);

if (!$reclamation) {
    header("Location: manage-reclamations.php");
    exit;
}

$success = $error = "";
$statuses = [
    'non_assignee' => 'En attente',
    'en_cours' => 'En cours',
    'resolue' => 'Resolue'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statut = $_POST['statut'] ?? $reclamation['statut'];
    $remarque = trim($_POST['remarque'] ?? '');

    if ($remarque === '') {
        $error = "La remarque ne peut pas etre vide.";
    } else {
        $remarkModel->add($reclamationId, $agentId, $statut, $remarque);
        $reclamationModel->updateStatus($reclamationId, $statut);
        $success = "Remarque enregistree et statut mis a jour.";
        $reclamation = $reclamationModel->getDetailsForAgent($reclamationId, $agentId);
    }
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
        <div class="section">
            <div class="section-header">
                <h1>Ajouter une remarque pour #REC-<?= htmlspecialchars($reclamationId) ?></h1>
                <p class="topbar-subtitle">Ces remarques ne sont visibles que par les agents et l'administrateur.</p>
            </div>

            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form class="card form-card" method="post">
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select id="statut" name="statut">
                        <?php foreach ($statuses as $key => $label): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= $reclamation['statut'] === $key ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remarque">Remarque</label>
                    <textarea id="remarque" name="remarque" rows="4" required><?= htmlspecialchars($_POST['remarque'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer la remarque</button>
                    <a class="btn btn-secondary" href="reclamation-agent-details.php?id=<?= urlencode($reclamationId) ?>">Retour</a>
                </div>
            </form>
        </div>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
