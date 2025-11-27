<?php
session_start();
$page_title  = "Tableau de Bord - Administrateur";
$active_menu = "dashboard";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';
require_once __DIR__ . '/../php/models/Reclamation.php';
require_once __DIR__ . '/../php/models/Affectation.php';

$pdo = Database::getInstance();

$userModel = new User($pdo);
$recModel  = new Reclamation($pdo);
$affModel  = new Affectation($pdo);

/* =======================
   DASHBOARD COUNTS
   ======================= */
$totalUsers       = count($userModel->getAllClients());
$totalAgents      = count($userModel->getAllAgents());
$totalRecs        = count($recModel->getAll());
$pendingRecs      = $recModel->countByStatus("non_assignee");
$inProgressRecs   = $recModel->countByStatus("en_cours");
$resolvedRecs     = $recModel->countByStatus("resolue");
$recentRecs       = $recModel->getRecent(5);

include 'includes/admin-header.php';
?>

<div class="layout">

    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div class="topbar-left">
                <h1>Tableau de Bord Administrateur</h1>
                <p class="topbar-subtitle">Vue d’ensemble sur l’activité du système.</p>
            </div>

            <div class="topbar-user">
                <img src="../images/logo.png" class="avatar-circle">

                <div>
                    <span class="topbar-username">
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                    </span>
                    <span class="topbar-role">Administrateur</span>
                </div>
            </div>
        </header>

        <!-- === STAT CARDS === -->
        <section class="cards-grid">

            <div class="card-stat">
                <h3>Réclamations Totales</h3>
                <p class="stat-number"><?= $totalRecs ?></p>
                <p class="stat-label">Toutes les réclamations enregistrées</p>
            </div>

            <div class="card-stat">
                <h3>Non assignées</h3>
                <p class="stat-number stat-warning"><?= $pendingRecs ?></p>
                <p class="stat-label">En attente d’un agent</p>
            </div>

            <div class="card-stat">
                <h3>En cours</h3>
                <p class="stat-number stat-info"><?= $inProgressRecs ?></p>
                <p class="stat-label">Traitement en cours</p>
            </div>

            <div class="card-stat">
                <h3>Résolues</h3>
                <p class="stat-number stat-success"><?= $resolvedRecs ?></p>
                <p class="stat-label">Cas clôturés</p>
            </div>

        </section>

        <!-- === RECENT RECLAMATIONS === -->
        <div class="card">
            <div class="card-header">
                <h2>Réclamations Récentes</h2>
                <a href="manage-reclamations.php" class="btn btn-secondary btn-sm">Voir tout</a>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Objet</th>
                    <th>Statut</th>
                    <th>Priorité</th>
                    <th>Agent</th>
                </tr>
                </thead>

                <tbody>

                <?php foreach ($recentRecs as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['client']) ?></td>
                        <td><?= htmlspecialchars($r['objet']) ?></td>

                        <td>
                            <span class="badge <?= $r['statut'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $r['statut'])) ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge <?= $r['priorite'] ?>">
                                <?= ucfirst($r['priorite']) ?>
                            </span>
                        </td>

                        <td><?= htmlspecialchars($r['agent'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </main>

</div>

<?php include 'includes/admin-footer.php'; ?>
