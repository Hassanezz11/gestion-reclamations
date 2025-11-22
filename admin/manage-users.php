<?php
session_start();

$page_title  = "Gestion des Utilisateurs";
$active_menu = "users";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Utilisateurs</h1>
                <p class="topbar-subtitle">Visualisez et contrôlez les comptes clients de la plateforme.</p>
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
                <div class="card-toolbar-left">
                    <select class="input-select">
                        <option>Tous les statuts</option>
                        <option>Actif</option>
                        <option>Bloqué</option>
                    </select>
                </div>
                <div class="card-toolbar-right">
                    <input type="text" class="input-search" placeholder="Rechercher un utilisateur...">
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
                            <th>Date d’inscription</th>
                            <th>Réclamations</th>
                            <th>Statut</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Fatima Zahra</td>
                            <td>fatima@example.com</td>
                            <td>01/10/2025</td>
                            <td>5</td>
                            <td><span class="badge badge-success">Actif</span></td>
                            <td class="table-actions">
                                <button class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Oussama Idrissi</td>
                            <td>oussama@example.com</td>
                            <td>22/09/2025</td>
                            <td>2</td>
                            <td><span class="badge badge-danger">Bloqué</span></td>
                            <td class="table-actions">
                                <button class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-success btn-sm">
                                    <i class="fas fa-unlock"></i>
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
