<?php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/../php/controllers/UserController.php';

$page_title  = "Détails de la réclamation";
$active_menu = "list";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Utilisateur';
$userId = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    redirect_to('user/my-reclamations.php');
}

$reclamationId = (int)$_GET['id'];

$userController = new UserController();
$reclamation = $userController->getReclamationDetails($reclamationId, $userId);

if (!$reclamation) {
    redirect_to('user/my-reclamations.php');
}

$messages = $userController->getReclamationMessages($reclamationId);
$remarks = $userController->getReclamationRemarks($reclamationId);

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
        <a href="my-reclamations.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour aux réclamations
        </a>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="details-container">
            <section class="section">
                <div class="section-header">
                    <h1><?= htmlspecialchars($reclamation['objet']) ?> - <?= formatReclamationId($reclamation['id']) ?></h1>
                    <span class="badge <?= getStatusBadge($reclamation['statut']) ?>"><?= getStatusText($reclamation['statut']) ?></span>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <label>Catégorie</label>
                        <p><?= htmlspecialchars($reclamation['categorie'] ?? 'Non définie') ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Sous-catégorie</label>
                        <p><?= htmlspecialchars($reclamation['sous_categorie'] ?? 'Non définie') ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Date de soumission</label>
                        <p><?= date('d/m/Y à H:i', strtotime($reclamation['date_creation'])) ?></p>
                    </div>
                    <div class="detail-item">
                        <label>Priorité</label>
                        <p><span class="priority-badge <?= getPriorityBadge($reclamation['priorite']) ?>"><?= getPriorityText($reclamation['priorite']) ?></span></p>
                    </div>
                    <?php if ($reclamation['agent_nom']): ?>
                    <div class="detail-item">
                        <label>Agent assigné</label>
                        <p><?= htmlspecialchars($reclamation['agent_nom']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <hr>

                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($reclamation['description'])) ?></p>

                <?php if ($reclamation['piece_jointe']): ?>
                <hr>

                <h3>Pièce jointe</h3>
                <div class="attachment">
                    <i class="fas fa-file"></i>
                    <span><?= htmlspecialchars($reclamation['piece_jointe']) ?></span>
                    <a href="../uploads/reclamations/<?= htmlspecialchars($reclamation['piece_jointe']) ?>" class="btn btn-sm btn-secondary" download>Télécharger</a>
                </div>
                <?php endif; ?>
            </section>

            <section class="section">
                <h2>Historique et remarques</h2>

                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Réclamation soumise</h4>
                            <p class="timestamp"><?= date('d/m/Y à H:i', strtotime($reclamation['date_creation'])) ?></p>
                            <p>Votre réclamation a été enregistrée avec succès.</p>
                        </div>
                    </div>

                    <?php if ($reclamation['agent_nom']): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Assignée à un agent</h4>
                            <p class="timestamp"><?= date('d/m/Y à H:i', strtotime($reclamation['date_creation'])) ?></p>
                            <p>Assignée à <?= htmlspecialchars($reclamation['agent_nom']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php foreach($remarks as $remark): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Remarque d'agent</h4>
                            <p class="timestamp"><?= date('d/m/Y à H:i', strtotime($remark['date_creation'])) ?></p>
                            <p><?= nl2br(htmlspecialchars($remark['remarque'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($remarks) && !$reclamation['agent_nom']): ?>
                    <p style="text-align: center; color: #666;">Aucune remarque pour le moment</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="section">
                <h2>Messages</h2>

                <div class="chat-container">
                    <div class="message-group" id="messageContainer">
                        <?php if (empty($messages)): ?>
                            <p style="text-align: center; color: #666; padding: 20px;">Aucun message pour le moment</p>
                        <?php else: ?>
                            <?php foreach($messages as $msg): ?>
                                <?php
                                $isCurrentUser = ($msg['utilisateur_id'] == $userId);
                                $messageClass = $isCurrentUser ? 'message-self' : 'message-other';
                                ?>
                                <div class="message <?= $messageClass ?>">
                                    <?php if (!$isCurrentUser): ?>
                                        <div class="message-sender"><?= htmlspecialchars($msg['nom_complet']) ?></div>
                                    <?php endif; ?>
                                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                    <span class="message-time"><?= date('d/m/Y H:i', strtotime($msg['date_creation'])) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <form class="message-form" method="POST" action="send-message.php" id="messageForm">
                        <input type="hidden" name="reclamation_id" value="<?= $reclamationId ?>">
                        <input type="text" name="message" id="messageInput" placeholder="Votre message..." required>
                        <button type="submit" class="btn btn-primary btn-icon">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/user-footer.php'; ?>
