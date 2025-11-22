<?php
session_start();

$page_title  = "Gestion des Réclamations";
$active_menu = "reclamations";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Réclamations</h1>
                <p class="topbar-subtitle">Filtrez, affectez et suivez l’état des réclamations.</p>
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

            <div class="card card-toolbar">
                <div class="card-toolbar-left filters-inline">
                    <select class="input-select">
                        <option>Statut : Tous</option>
                        <option>Non affectée</option>
                        <option>En cours</option>
                        <option>Résolue</option>
                    </select>

                    <select class="input-select">
                        <option>Priorité : Toutes</option>
                        <option>Faible</option>
                        <option>Moyenne</option>
                        <option>Haute</option>
                    </select>
                </div>
                <div class="card-toolbar-right">
                    <input type="text" class="input-search" placeholder="Rechercher par client, objet...">
                </div>
            </div>

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
                            <th>Statut</th>
                            <th>Agent</th>
                            <th style="width: 220px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1562</td>
                            <td>Salma</td>
                            <td>Facture erronée</td>
                            <td>Facturation</td>
                            <td><span class="badge badge-warning">En cours</span></td>
                            <td>Youssef</td>
                            <td class="table-actions">
                                <button class="btn btn-secondary btn-sm"
                                        onclick="window.location.href='reclamation-details.php'">
                                    <i class="fas fa-eye"></i>&nbsp; Détails
                                </button>
                                <button class="btn btn-primary btn-sm"
                                        onclick="window.location.href='assign-agent.php'">
                                    <i class="fas fa-user-plus"></i>&nbsp; Assigner
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>1540</td>
                            <td>Hamza</td>
                            <td>Retard de livraison</td>
                            <td>Livraison</td>
                            <td><span class="badge badge-normal">Non affectée</span></td>
                            <td>-</td>
                            <td class="table-actions">
                                <button class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i>&nbsp; Détails
                                </button>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-user-plus"></i>&nbsp; Assigner
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
