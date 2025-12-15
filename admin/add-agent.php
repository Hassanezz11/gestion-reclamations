<?php
require_once __DIR__ . '/../php/auth.php';
Auth::requireRole('admin');

$page_title  = "Nouvel Agent";
$active_menu = "agents";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/User.php';

$pdo = Database::getInstance();
$userModel = new User($pdo);

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom       = trim($_POST['nom_complet'] ?? "");
    $email     = trim($_POST['email'] ?? "");
    $telephone = trim($_POST['telephone'] ?? "");
    $password  = trim($_POST['password'] ?? "");

    if ($nom === "" || $email === "" || $password === "") {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($telephone !== "" && !preg_match('/^[0-9\s\-\+\(\)]{8,20}$/', $telephone)) {
        $error = "Le numéro de téléphone n'est pas valide.";
    } else {

        // 1) Check email existence via Model
        $existing = $userModel->findByEmail($email);

        if ($existing) {
            $error = "Cet email est déjà utilisé.";
        }
        else {

            // 2) Create agent
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $created = $userModel->create($nom, $email, $passwordHash, "agent");

            if ($created) {
                $success = "Agent ajouté avec succès !";
            } else {
                $error = "Erreur lors de l'ajout de l'agent.";
            }
        }
    }
}

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Nouvel Agent</h1>
                <p class="topbar-subtitle">Créer un nouvel agent du support.</p>
            </div>
        </header>

        <section class="content">
            <div class="card" style="max-width:600px; margin:auto;">

                <div class="card-header"><h2>Ajouter un agent</h2></div>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nom Complet *</label>
                        <input type="text" class="input-text" name="nom_complet" required>
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" class="input-text" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" class="input-text" name="telephone">
                    </div>

                    <div class="form-group">
                        <label>Mot de Passe *</label>
                        <input type="password" class="input-text" name="password" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Ajouter
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
