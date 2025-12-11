<?php
session_start();
require_once __DIR__ . '/../php/config.php';
$page_title  = "Profil Administrateur";
$active_menu = "profile";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    redirect_to('auth/login.php');
}

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    redirect_to('auth/login.php');
}

$admin  = $userModel->findById($userId);

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom      = trim($_POST['nom_complet']);
    $email    = trim($_POST['email']);
    $tel      = trim($_POST['telephone']);
    $adresse  = trim($_POST['adresse']);
    $password = trim($_POST['password']);

    if ($nom === "" || $email === "") {
        $error = "Veuillez remplir les champs obligatoires.";
    } else {
        $userModel->updateProfile($userId, $nom, $email, $tel, $adresse);

        if ($password !== "") {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userModel->updatePassword($userId, $hash);
        }

        $_SESSION['user_name'] = $nom;
        $success = "Profil mis à jour avec succès.";
    }

    $admin = $userModel->findById($userId); // refresh
}

include 'includes/admin-header.php';
?>

<div class="layout">
<?php include 'includes/admin-sidebar.php'; ?>
<main class="main">

    <header class="topbar">
        <h1>Profil Administrateur</h1>
        <p class="topbar-subtitle">Mettre à jour vos informations personnelles.</p>
    </header>

    <section class="content">

        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

        <div class="card" style="max-width:700px;margin:auto;">

            <div class="card-header"><h2>Mes informations</h2></div>

            <form method="POST">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" class="input-text" name="nom_complet" value="<?= htmlspecialchars($admin['nom_complet']) ?>">
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" class="input-text" name="email" value="<?= htmlspecialchars($admin['email']) ?>">
                </div>

                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" class="input-text" name="telephone" value="<?= htmlspecialchars($admin['telephone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" class="input-text" name="adresse" value="<?= htmlspecialchars($admin['adresse'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Nouveau mot de passe (optionnel)</label>
                    <input type="password" class="input-text" name="password">
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>

        </div>

    </section>

</main>
</div>

<?php include 'includes/admin-footer.php'; ?>
