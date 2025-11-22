<?php
session_start();

$page_title  = "Fiche Agent";
$active_menu = "agents";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Fiche Agent</h1>
                <p class="topbar-subtitle">Créer ou modifier les informations d’un agent de support.</p>
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

            <div class="card" style="max-width: 720px; margin: 0 auto;">
                <div class="card-header">
                    <h2>Détails de l’agent</h2>
                </div>

                <form>
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" class="input-text" name="nom_complet"
                               placeholder="Nom et prénom de l’agent">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="input-text" name="email" placeholder="agent@exemple.com">
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" class="input-text" name="telephone" placeholder="+212 6 ...">
                    </div>

                    <div class="form-group">
                        <label>Statut</label>
                        <select class="input-select" name="statut">
                            <option>Actif</option>
                            <option>En pause</option>
                            <option>Désactivé</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nouveau mot de passe (optionnel)</label>
                        <input type="password" class="input-text" name="password">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>&nbsp; Enregistrer
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
