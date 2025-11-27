<?php
session_start();

$page_title  = "Modifier Agent";
$active_menu = "agents";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);

$success = $error = "";
$agentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get agent info
$agent = $userModel->findById($agentId);

if (!$agent || $agent['role'] !== 'agent') {
    die("<h2>Agent introuvable.</h2>");
}

// Process update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom       = trim($_POST['nom_complet'] ?? "");
    $email     = trim($_POST['email'] ?? "");
    $telephone = trim($_POST['telephone'] ?? "");
    $password  = trim($_POST['password'] ?? "");

    if ($nom === "" || $email === "") {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {

        // Update base info
        $stmt = $pdo->prepare("
            UPDATE utilisateurs 
            SET nom_complet = ?, email = ?, telephone = ?
            WHERE id = ? AND role = 'agent'
        ");
        $stmt->execute([$nom, $email, $telephone, $agentId]);

        // Update password if provided
        if ($password !== "") {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE utilisateurs SET mot_de_passe=? WHERE id=?")
                ->execute([$hash, $agentId]);
        }

        $success = "Informations mises à jour.";
        $agent = $userModel->findById($agentId); // refresh data
    }
}

include 'includes/admin-header.php';
?>

<div class="layout">

    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Modifier Agent</h1>
                <p class="topbar-subtitle">Modifier les informations d’un agent.</p>
            </div>
        </header>

        <section class="content">

            <div class="card" style="max-width:700px; margin:auto;">
                <div class="card-header">
                    <h2><?= htmlspecialchars($agent['nom_complet']) ?></h2>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nom complet *</label>
                        <input type="text" class="input-text" name="nom_complet"
                               value="<?= htmlspecialchars($agent['nom_complet']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" class="input-text" name="email"
                               value="<?= htmlspecialchars($agent['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" class="input-text" name="telephone"
                               value="<?= htmlspecialchars($agent['telephone'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Nouveau mot de passe (optionnel)</label>
                        <input type="password" class="input-text" name="password">
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                            Annuler
                        </button>
                    </div>

                </form>

            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
