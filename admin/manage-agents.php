<?php
session_start();
$page_title  = "Gestion des Agents";
$active_menu = "agents";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';
require_once __DIR__ . '/../php/models/Affectation.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);
$affModel  = new Affectation($pdo);

// DELETE agent
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND role = 'agent'")->execute([$id]);
    header("Location: manage-agents.php?success=1");
    exit;
}

$agents = $userModel->getAllAgents();

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Agents</h1>
                <p class="topbar-subtitle">Ajouter, modifier et suivre les agents du support.</p>
            </div>
        </header>

        <section class="content">

            <div class="card card-toolbar">
                <div class="card-toolbar-left">
                    <a href="add-agent.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus"></i> &nbsp; Nouvel agent
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Liste des Agents</h2>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Réclamations assignées</th>
                            <th style="width:160px;">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($agents as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nom_complet']) ?></td>
                                <td><?= htmlspecialchars($a['email']) ?></td>
                                <td><?= htmlspecialchars($a['telephone'] ?? '—') ?></td>
                                <td>
                                    <?= $affModel->countByAgent($a['id']) ?>
                                </td>

                                <td class="table-actions">
                                    <a class="btn btn-secondary btn-sm"
                                       href="edit-agent.php?id=<?= $a['id'] ?>">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <a class="btn btn-danger btn-sm"
                                       onclick="return confirm('Supprimer cet agent ?')"
                                       href="manage-agents.php?delete=<?= $a['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
