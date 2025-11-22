<?php
session_start();

$page_title  = "Profil Administrateur";
$active_menu = "profile";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Profil Administrateur</h1>
                <p class="topbar-subtitle">Mettez à jour vos informations personnelles et votre mot de passe.</p>
            </div>

            <div class="topbar-user">
                <img src="../images/logo.png" alt="Avatar" class="avatar-circle">
                <div>
                    <span class="topbar-username"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                    <span class="topbar-role">Administrateur</span>
                </div>
            </div>
        </header>

        <section class="content">

            <div class="grid-2-columns">
                <!-- Infos profil -->
                <div class="card">
                    <div class="card-header">
                        <h2>Informations du compte</h2>
                    </div>

                    <form>
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" class="input-text" value="<?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin Principal') ?>">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="input-text" value="admin@example.com">
                        </div>

                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-save"></i>&nbsp; Mettre à jour
                        </button>
                    </form>
                </div>

                <!-- Mot de passe -->
                <div class="card">
                    <div class="card-header">
                        <h2>Changer de mot de passe</h2>
                    </div>

                    <form>
                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" class="input-text">
                        </div>

                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" class="input-text">
                        </div>

                        <div class="form-group">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" class="input-text">
                        </div>

                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-key"></i>&nbsp; Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
