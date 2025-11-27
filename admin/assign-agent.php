<?php
session_start();
$page_title  = "Affecter un Agent";
$active_menu = "reclamations";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';
require_once __DIR__ . '/../php/models/Reclamation.php';
require_once __DIR__ . '/../php/models/Affectation.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);
$recModel  = new Reclamation($pdo);
$affModel  = new Affectation($pdo);

$recId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$reclamation = $recModel->getById($recId);
$agents = $userModel->getAllAgents();

$success = $error = "";

/* PROCESS ASSIGNMENT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $agentId = (int)$_POST['agent_id'];

    if ($agentId <= 0) {
        $error = "Veuillez sélectionner un agent.";
    } else {
        $affModel->assign($recId, $agentId);
        $recModel->updateStatus($recId, "en_cours");
        $success = "Agent assigné avec succès.";
    }
}

include 'includes/admin-header.php';
?>

<div class="layout">

    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Affecter un Agent</h1>
                <p class="topbar-subtitle">Sélectionnez l’agent chargé du traitement.</p>
            </div>
        </header>

        <section class="content">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="grid-2-columns">

                <!-- Reclamation Summary -->
                <div class="card">
                    <div class="card-header"><h2>Réclamation #<?= $reclamation['id'] ?></h2></div>

                    <p><strong>Client:</strong> <?= htmlspecialchars($reclamation['client']) ?></p>
                    <p><strong>Objet:</strong> <?= htmlspecialchars($reclamation['objet']) ?></p>
                    <p><strong>Priorité:</strong> <?= ucfirst($reclamation['priorite']) ?></p>
                    <p><strong>Statut:</strong> <?= strtoupper($reclamation['statut']) ?></p>
                </div>

                <!-- Assign Form -->
                <div class="card">
                    <div class="card-header"><h2>Choisir un agent</h2></div>

                    <form method="POST">

                        <div class="form-group">
                            <label>Agent</label>
                            <select name="agent_id" class="input-select">
                                <option value="">-- Sélectionner --</option>

                                <?php foreach ($agents as $a): ?>
                                    <option value="<?= $a['id'] ?>">
                                        <?= htmlspecialchars($a['nom_complet']) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <button class="btn btn-primary">Valider</button>
                        <button type="button" class="btn btn-secondary" onclick="history.back()">Annuler</button>

                    </form>
                </div>

            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
