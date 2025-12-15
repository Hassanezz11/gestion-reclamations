<?php
require_once __DIR__ . '/../php/auth.php';
Auth::requireRole('agent');

$page_title  = "Profil Agent";
$active_menu = "profile";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);

$userId = $_SESSION['user_id'];
$agent = $userModel->findById($userId);

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom      = trim($_POST['nom_complet']);
    $email    = trim($_POST['email']);
    $tel      = trim($_POST['telephone']);
    $password = trim($_POST['password']);

    if ($nom === "" || $email === "") {
        $error = "Veuillez remplir les champs obligatoires.";
    } else {
        $userModel->updateProfile($userId, $nom, $email, $tel, $agent['adresse']);

        if ($password !== "") {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userModel->updatePassword($userId, $hash);
        }

        $_SESSION['user_name'] = $nom;
        $success = "Profil mis à jour.";
    }

    $agent = $userModel->findById($userId); // refresh
}

include __DIR__ . '/includes/agent-header.php';
?>

<div class="layout">
<?php include __DIR__ . '/includes/agent-sidebar.php'; ?>
<main class="main">

    <header class="topbar">
        <h1>Profil Agent</h1>
        <p class="topbar-subtitle">Modifier les informations de votre compte agent.</p>
    </header>

    <section class="content">

        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

        <div class="card" style="max-width:700px;margin:auto;">
            <div class="card-header"><h2>Mes informations</h2></div>

            <form method="POST">

                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" class="input-text" name="nom_complet"
                           value="<?= htmlspecialchars($agent['nom_complet']) ?>">
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" class="input-text" name="email"
                           value="<?= htmlspecialchars($agent['email']) ?>">
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
                    <button class="btn btn-primary">Enregistrer</button>
                </div>

            </form>

        </div>

    </section>

</main>
</div>

<?php include __DIR__ . '/includes/agent-footer.php'; ?>
