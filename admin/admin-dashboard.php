<?php
session_start();
$page_title  = "Tableau de Bord - Administrateur";
$active_menu = "dashboard";

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
                <img src="../images/logo.png" alt="Avatar" class="avatar-circle">
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
                <p class="stat-number">120</p>
                <p class="stat-label">Toutes les réclamations enregistrées</p>
            </div>

            <div class="card-stat">
                <h3>En attente</h3>
                <p class="stat-number stat-warning">14</p>
                <p class="stat-label">Sans agent assigné</p>
            </div>

            <div class="card-stat">
                <h3>Résolues</h3>
                <p class="stat-number stat-success">88</p>
                <p class="stat-label">Cas clôturés avec succès</p>
            </div>

            <div class="card-stat">
                <h3>Agents actifs</h3>
                <p class="stat-number">6</p>
                <p class="stat-label">En service</p>
            </div>
        </section>


        <!-- === RECLAMATIONS TABLE === -->
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
                    <tr>
                        <td>1562</td>
                        <td>Salma</td>
                        <td>Facture erronée</td>
                        <td><span class="badge badge-warning">En cours</span></td>
                        <td><span class="badge badge-danger">Haute</span></td>
                        <td>Youssef</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

</div>

<?php include 'includes/admin-footer.php'; ?>
