<?php
session_start();
$page_title  = "Détails Réclamation";
$active_menu = "reclamations";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Reclamation.php';
require_once __DIR__ . '/../php/models/Affectation.php';
require_once __DIR__ . '/../php/models/User.php';
require_once __DIR__ . '/../php/models/Message.php';
require_once __DIR__ . '/../php/models/Remark.php';

$pdo = Database::getInstance();
$recModel = new Reclamation($pdo);
$affModel = new Affectation($pdo);
$userModel = new User($pdo);
$msgModel = new Message($pdo);
$remarkModel = new Remark($pdo);

$recId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$rec = $recModel->getById($recId);

if (!$rec) die("<h2>Réclamation introuvable</h2>");

$agent = $affModel->getAgentForReclamation($recId);
$messages = $msgModel->getByReclamation($recId);
$remarks  = $remarkModel->getByReclamation($recId);

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Réclamation #<?= $rec['id'] ?></h1>
                <p class="topbar-subtitle"><?= htmlspecialchars($rec['objet']) ?></p>
            </div>
        </header>

        <section class="content">

            <div class="grid-2-columns">

                <!-- INFO -->
                <div class="card">
                    <div class="card-header"><h2>Informations</h2></div>

                    <p><strong>Client :</strong> <?= htmlspecialchars($rec['client']) ?></p>
                    <p><strong>Objet :</strong> <?= htmlspecialchars($rec['objet']) ?></p>
                    <p><strong>Priorité :</strong> <?= ucfirst($rec['priorite']) ?></p>
                    <p><strong>Statut :</strong> <?= ucfirst($rec['statut']) ?></p>

                    <p><strong>Agent assigné :</strong>
                        <?= $agent ? htmlspecialchars($agent['nom_complet']) : "—" ?>
                    </p>

                    <a href="assign-agent.php?id=<?= $rec['id'] ?>"
                       class="btn btn-primary btn-sm mt-1">
                        Affecter un agent
                    </a>
                </div>

                <!-- MESSAGES -->
                <div class="card">
                    <div class="card-header"><h2>Messages</h2></div>

                    <?php foreach ($messages as $m): ?>
                        <div class="message-item">
                            <strong><?= htmlspecialchars($m['nom_complet']) ?></strong><br>
                            <p><?= nl2br(htmlspecialchars($m['message'])) ?></p>
                            <small><?= $m['date_creation'] ?></small>
                        </div>
                        <hr>
                    <?php endforeach; ?>

                    <?php if (empty($messages)): ?>
                        <p class="text-muted">Aucun message.</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- REMARKS -->
            <div class="card mt-2">
                <div class="card-header"><h2>Remarques internes</h2></div>

                <?php foreach ($remarks as $r): ?>
                    <div class="remark-item">
                        <strong><?= htmlspecialchars($r['nom_complet']) ?></strong>
                        — <em><?= htmlspecialchars($r['statut']) ?></em>
                        <p><?= nl2br(htmlspecialchars($r['remarque'])) ?></p>
                        <small><?= $r['date_creation'] ?></small>
                    </div>
                    <hr>
                <?php endforeach; ?>

                <?php if (empty($remarks)): ?>
                    <p class="text-muted">Aucune remarque.</p>
                <?php endif; ?>
            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
