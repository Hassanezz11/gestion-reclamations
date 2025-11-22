<?php
session_start();

$page_title  = "Gestion des Agents";
$active_menu = "agents";

include 'includes/admin-header.php';
?>

<div class="layout">

    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Agents</h1>
                <p class="topbar-subtitle">Ajouter, modifier et suivre les agents en charge des réclamations.</p>
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
                    <button class="btn btn-primary btn-sm" onclick="window.location.href='edit-agent.php'">
                        <i class="fas fa-user-plus"></i>&nbsp; Nouvel agent
                    </button>
                </div>
                <div class="card-toolbar-right">
                    <input type="text" class="input-search" placeholder="Rechercher un agent...">
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Liste des agents</h2>
                    <span class="badge badge-normal">Démo statique</span>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Réclamations en cours</th>
                            <th>Statut</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Youssef El Arabi</td>
                            <td>youssef@example.com</td>
                            <td>+212 6 12 34 56 78</td>
                            <td>8</td>
                            <td><span class="badge badge-success">Actif</span></td>
                            <td class="table-actions">
                                <button class="btn btn-secondary btn-sm" onclick="window.location.href='edit-agent.php'">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Imane Chafik</td>
                            <td>imane@example.com</td>
                            <td>+212 6 98 76 54 32</td>
                            <td>3</td>
                            <td><span class="badge badge-warning">En pause</span></td>
                            <td class="table-actions">
                                <button class="btn btn-secondary btn-sm">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
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
