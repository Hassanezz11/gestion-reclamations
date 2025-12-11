<?php
session_start();
require_once __DIR__ . '/../php/config.php';
$page_title  = "Envoyer un message au client";
$active_menu = "reclamations";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'agent') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Agent';
$agentId  = (int)($_SESSION['user_id'] ?? 0);
$reclamationId = (int)($_GET['id'] ?? 0);

if ($reclamationId <= 0) {
    header("Location: manage-reclamations.php");
    exit;
}

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Reclamation.php';
require_once __DIR__ . '/../php/models/Message.php';

$pdo = Database::getInstance();
$reclamationModel = new Reclamation($pdo);
$messageModel = new Message($pdo);

$reclamation = $reclamationModel->getDetailsForAgent($reclamationId, $agentId);

if (!$reclamation) {
    header("Location: manage-reclamations.php");
    exit;
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');

    if ($message === '') {
        $error = "Le message ne peut pas etre vide.";
    } else {
        $messageModel->send($reclamationId, $agentId, $message);
        $success = "Message envoye au client.";
    }
}

$messages = $messageModel->getByReclamation($reclamationId);

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
                <div>
                    <h1>Conversation avec <?= htmlspecialchars($reclamation['client']) ?></h1>
                    <p class="topbar-subtitle">Vue type messagerie directe : flux continu, bulles alignÇ¸es comme sur Instagram.</p>
                </div>
                <a class="btn btn-secondary btn-sm" href="reclamation-agent-details.php?id=<?= urlencode($reclamationId) ?>">Retour aux details</a>
            </div>

            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <div class="chat-container" style="max-width:800px;margin:auto;">
                <div class="message-group" style="max-height:420px;overflow-y:auto;padding:12px; background:linear-gradient(135deg,#f7f8fb,#eef1f7); border-radius:12px; border:1px solid #e5e7eb;">
                    <?php if (empty($messages)): ?>
                        <p style="text-align:center;color:#6b7280;">Aucun message pour l'instant. Ouvrez la conversation avec le client.</p>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <?php $isAgent = ((int)$msg['utilisateur_id']) === $agentId; ?>
                            <div class="message <?= $isAgent ? 'message-self' : 'message-other' ?>" style="margin-bottom:12px; display:flex; flex-direction:column; align-items:<?= $isAgent ? 'flex-end' : 'flex-start' ?>;">
                                <div style="font-size:12px;color:#9ca3af; margin-bottom:4px;"><?= $isAgent ? 'Vous (Agent)' : htmlspecialchars($msg['nom_complet']) ?></div>
                                <div style="background:<?= $isAgent ? '#1f7aec' : '#ffffff' ?>; color:<?= $isAgent ? '#ffffff' : '#111827' ?>; padding:10px 14px; border-radius:18px; max-width:70%; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
                                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                </div>
                                <span class="message-time" style="font-size:11px;color:#9ca3af; margin-top:4px;">
                                    <?= htmlspecialchars(date('d/m/Y H:i', strtotime($msg['date_creation']))) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form class="message-form" method="post" style="margin-top:12px; display:flex; gap:8px; align-items:center;">
                    <input type="text" id="messageInput" name="message" placeholder="Ecrire un message..." required
                        value="<?= htmlspecialchars($_POST['message'] ?? '') ?>"
                        style="flex:1; border-radius:24px; border:1px solid #d1d5db; padding:12px 16px;">
                    <button type="submit" class="btn btn-primary btn-icon" style="border-radius:999px; padding:12px 18px;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

</main>
</div>

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
