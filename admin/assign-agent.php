<?php
session_start();

$page_title  = "Affecter un Agent";
$active_menu = "reclamations";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Affecter un Agent à une Réclamation</h1>
                <p class="topbar-subtitle">Choisissez l’agent le plus adapté pour traiter cette demande.</p>
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
                <!-- Résumé réclamation -->
                <div class="card">
                    <div class="card-header">
                        <h2>Réclamation #1562</h2>
                    </div>
                    <p><strong>Client :</strong> Salma</p>
                    <p><strong>Objet :</strong> Facture erronée</p>
                    <p><strong>Catégorie :</strong> Facturation / Erreur</p>
                    <p><strong>Priorité :</strong> Haute</p>
                    <p><strong>Statut actuel :</strong> En cours</p>
                </div>

                <!-- Formulaire d’affectation -->
                <div class="card">
                    <div class="card-header">
                        <h2>Choisir un agent</h2>
                    </div>

                    <form>
                        <div class="form-group">
                            <label>Agent</label>
                            <select class="input-select" name="agent_id">
                                <option value="">-- Sélectionner un agent --</option>
                                <option value="1">Youssef El Arabi (8 réclamations en cours)</option>
                                <option value="2">Imane Chafik (3 réclamations en cours)</option>
                                <option value="3">Nadia Boulahcen (1 réclamation en cours)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Commentaire (optionnel)</label>
                            <textarea class="input-text" rows="3" placeholder="Instructions pour l’agent..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i>&nbsp; Valider l’affectation
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="history.back()">
                            Annuler
                        </button>
                    </form>
                </div>
            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
