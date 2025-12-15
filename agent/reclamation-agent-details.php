<?php
require_once __DIR__ . '/../php/auth.php';
Auth::requireRole('agent');

$page_title  = "Details de la reclamation";
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
require_once __DIR__ . '/../php/models/Message.php';

$pdo = Database::getInstance();
$reclamationModel = new Reclamation($pdo);
$remarkModel = new Remark($pdo);
$messageModel = new Message($pdo);

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
    $action = $_POST['action'] ?? '';

    if ($action === 'status') {
        $newStatus = $_POST['statut'] ?? $reclamation['statut'];
        if (!array_key_exists($newStatus, $statuses)) {
            $error = "Statut invalide.";
        } else {
            $reclamationModel->updateStatus($reclamationId, $newStatus);
            $remarkModel->add($reclamationId, $agentId, $newStatus, "Statut mis a jour vers {$statuses[$newStatus]}");
            $success = "Statut mis a jour.";
        }
    } elseif ($action === 'remark') {
        $statut = $_POST['statut'] ?? $reclamation['statut'];
        $remarkText = trim($_POST['remarque'] ?? '');
        if ($remarkText === '') {
            $error = "La remarque ne peut pas etre vide.";
        } else {
            $remarkModel->add($reclamationId, $agentId, $statut, $remarkText);
            $reclamationModel->updateStatus($reclamationId, $statut);
            $success = "Remarque enregistree.";
        }
    } elseif ($action === 'message') {
        $message = trim($_POST['message'] ?? '');
        if ($message === '') {
            $error = "Le message ne peut pas etre vide.";
        } else {
            $messageModel->send($reclamationId, $agentId, $message);
            $success = "Message envoye au client.";
        }
    }

    $reclamation = $reclamationModel->getDetailsForAgent($reclamationId, $agentId);
}

$remarks = $remarkModel->getByReclamation($reclamationId);
$messages = $messageModel->getByReclamation($reclamationId);

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
        <a href="manage-reclamations.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour aux reclamations
        </a>

        <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <div class="details-container">
            <section class="section">
                <div class="section-header">
                    <h1>Reclamation #REC-<?= htmlspecialchars(str_pad((string)$reclamation['id'], 4, '0', STR_PAD_LEFT)) ?></h1>
                    <div class="status-actions">
                        <form method="post" style="display:flex;gap:8px;align-items:center;">
                            <input type="hidden" name="action" value="status">
                            <select id="statusSelect" name="statut" class="form-control-inline">
                                <?php foreach ($statuses as $key => $label): ?>
                                    <option value="<?= htmlspecialchars($key) ?>" <?= $reclamation['statut'] === $key ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-secondary btn-sm" type="submit">Mettre a jour</button>
                        </form>
                    </div>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <label>Client</label>
                        <p><?= htmlspecialchars($reclamation['client']) ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Email</label>
                        <p><?= htmlspecialchars($reclamation['client_email'] ?? '') ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Categorie</label>
                        <p><?= htmlspecialchars($reclamation['categorie'] ?? 'Non defini') ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Priorite</label>
                        <p><?= priorityBadge($reclamation['priorite']) ?></p>
                    </div>
                </div>

                <hr>

                <h3>Description de la reclamation</h3>
                <p><?= nl2br(htmlspecialchars($reclamation['description'])) ?></p>

                <?php if (!empty($reclamation['piece_jointe'])): ?>
                    <hr>
                    <h3>Piece jointe</h3>
                    <div class="attachment">
                        <i class="fas fa-file"></i>
                        <span><?= htmlspecialchars($reclamation['piece_jointe']) ?></span>
                        <a href="../uploads/<?= urlencode($reclamation['piece_jointe']) ?>" class="btn btn-sm btn-secondary">Telecharger</a>
                    </div>
                <?php endif; ?>
            </section>

            <section class="section">
                <h2>Remarques d'agent</h2>
                
                <form class="form-large" method="post">
                    <input type="hidden" name="action" value="remark">
                    <div class="form-group">
                        <label for="remarks">Ajouter une remarque</label>
                        <textarea id="remarks" name="remarque" placeholder="Vos remarques et observations..." required><?= htmlspecialchars($_POST['remarque'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="remarkStatus">Statut</label>
                        <select id="remarkStatus" name="statut">
                            <?php foreach ($statuses as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= $reclamation['statut'] === $key ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enregistrer la remarque</button>
                        <a href="add-remark.php?id=<?= urlencode($reclamationId) ?>" class="btn btn-secondary btn-sm">Ouvrir la page Remarque</a>
                    </div>
                </form>

                <div class="timeline">
                    <?php if (empty($remarks)): ?>
                        <p>Aucune remarque pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($remarks as $rem): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h4><?= htmlspecialchars($rem['nom_complet']) ?> - <?= htmlspecialchars($statuses[$rem['statut']] ?? $rem['statut']) ?></h4>
                                    <p class="timestamp"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($rem['date_creation']))) ?></p>
                                    <p><?= nl2br(htmlspecialchars($rem['remarque'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section class="section">
                <h2>Messages avec le client</h2>
                
                <div class="chat-container">
                    <div class="message-group">
                        <?php if (empty($messages)): ?>
                            <p>Aucun message echange pour l'instant.</p>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                                <?php $isAgent = ((int)$msg['utilisateur_id']) === $agentId; ?>
                                <div class="message <?= $isAgent ? 'message-self' : 'message-other' ?>">
                                    <div class="message-sender"><?= $isAgent ? 'Vous (Agent)' : htmlspecialchars($msg['nom_complet']) ?></div>
                                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                    <span class="message-time"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($msg['date_creation']))) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form class="message-form" method="post">
                        <input type="hidden" name="action" value="message">
                        <input type="text" id="messageInput" name="message" placeholder="Votre message..." required>
                        <button type="submit" class="btn btn-primary btn-icon">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <div style="margin-top:8px;">
                        <a class="btn btn-secondary btn-sm" href="send-message.php?id=<?= urlencode($reclamationId) ?>">Ouvrir la page Message</a>
                    </div>
                </div>
            </section>
        </div>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
