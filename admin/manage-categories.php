<?php
session_start();

$page_title  = "Gestion des Catégories";
$active_menu = "categories";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Gestion des Catégories</h1>
                <p class="topbar-subtitle">Organisez les réclamations par catégories et sous-catégories.</p>
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
                <!-- Catégories -->
                <div class="card">
                    <div class="card-header">
                        <h2>Catégories</h2>
                    </div>

                    <ul class="simple-list">
                        <li>
                            <span>Livraison</span>
                            <span class="list-actions">
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </span>
                        </li>
                        <li>
                            <span>Facturation</span>
                            <span class="list-actions">
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </span>
                        </li>
                        <li>
                            <span>Support technique</span>
                            <span class="list-actions">
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </span>
                        </li>
                    </ul>

                    <form class="mt-1">
                        <div class="form-group">
                            <label>Nouvelle catégorie</label>
                            <input type="text" class="input-text" placeholder="Nom de la catégorie">
                        </div>
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-plus"></i>&nbsp; Ajouter
                        </button>
                    </form>
                </div>

                <!-- Sous-catégories -->
                <div class="card">
                    <div class="card-header">
                        <h2>Sous-catégories</h2>
                    </div>

                    <div class="form-group">
                        <label>Catégorie</label>
                        <select class="input-select">
                            <option>Livraison</option>
                            <option>Facturation</option>
                            <option>Support technique</option>
                        </select>
                    </div>

                    <ul class="simple-list">
                        <li>
                            <span>Retard</span>
                            <span class="list-actions">
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </span>
                        </li>
                        <li>
                            <span>Colis perdu</span>
                            <span class="list-actions">
                                <button class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </span>
                        </li>
                    </ul>

                    <form class="mt-1">
                        <div class="form-group">
                            <label>Nouvelle sous-catégorie</label>
                            <input type="text" class="input-text" placeholder="Nom de la sous-catégorie">
                        </div>
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-plus"></i>&nbsp; Ajouter
                        </button>
                    </form>
                </div>
            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
