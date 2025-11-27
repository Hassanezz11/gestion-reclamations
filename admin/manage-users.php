<?php
session_start();

$page_title  = "Gestion des Utilisateurs";
$active_menu = "users";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';
require_once __DIR__ . '/../php/models/Reclamation.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);
$recModel  = new Reclamation($pdo);

$search = trim($_GET['q'] ?? "");

// GET USERS
if ($search !== "") {
    $users = $userModel->searchClients($search);
} else {
    $users = $userModel->getAllClients();
}

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Utilisateurs</h1>
            </div>
        </header>

        <section class="content">

            <div class="card card-toolbar">
                <div class="card-toolbar-right">
                    <form method="GET" style="display:flex; gap:0.5rem;">
                        <input type="text"
                               name="q"
                               class="input-search"
                               placeholder="Rechercher par nom ou email..."
                               value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-secondary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="manage-users.php" class="btn btn-light btn-sm">
                            Réinitialiser
                        </a>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Liste des utilisateurs</h2>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Date d’inscription</th>
                            <th>Réclamations</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Aucun utilisateur trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['nom_complet']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['telephone'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($u['adresse'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($u['date_creation']) ?></td>
                                    <td>
                                        <?= $userModel->countReclamations($u['id']) ?>
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
