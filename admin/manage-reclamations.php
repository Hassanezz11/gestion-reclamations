<?php
session_start();

$page_title  = "Gestion des Réclamations";
$active_menu = "reclamations";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Reclamation.php';

$pdo = Database::getInstance();
$recModel = new Reclamation($pdo);

// DELETE LOGIC
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id > 0) {
        $recModel->delete($id);
    }
    header("Location: manage-reclamations.php?deleted=1");
    exit;
}

// GET ALL RECLAMATIONS (WITH DETAILS)
$reclamations = $recModel->getAllWithDetails();

include 'includes/admin-header.php';
?>

<div class="layout">

    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Réclamations</h1>
                <p class="topbar-subtitle">Suivi détaillé des demandes clients.</p>
            </div>
        </header>

        <section class="content">

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Réclamation supprimée.</div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h2>Liste des réclamations</h2>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Objet</th>
                            <th>Catégorie</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Agent</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php if (empty($reclamations)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center;">Aucune réclamation trouvée.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reclamations as $r): ?>
                                <tr>
                                    <td><?= $r['id'] ?></td>
                                    <td><?= htmlspecialchars($r['client']) ?></td>
                                    <td><?= htmlspecialchars($r['objet']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($r['categorie'] ?? '—') ?>
                                        <?php if (!empty($r['sous_categorie'])): ?>
                                            <br><small><?= htmlspecialchars($r['sous_categorie']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $r['priorite'] ?>">
                                            <?= ucfirst($r['priorite']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $r['statut'] ?>">
                                            <?= ucfirst(str_replace('_', ' ', $r['statut'])) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($r['agent'] ?? '—') ?></td>

                                    <td class="table-actions">
                                        <a class="btn btn-secondary btn-sm"
                                           href="reclamation-details.php?id=<?= $r['id'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a class="btn btn-primary btn-sm"
                                           href="assign-agent.php?id=<?= $r['id'] ?>">
                                            <i class="fas fa-user-tie"></i>
                                        </a>

                                        <a class="btn btn-danger btn-sm"
                                           onclick="return confirm('Supprimer cette réclamation ?');"
                                           href="manage-reclamations.php?delete=<?= $r['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
