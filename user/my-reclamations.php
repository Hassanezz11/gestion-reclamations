<?php
session_start();
$page_title  = "Mes réclamations";
$active_menu = "list";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header("Location: /auth/login.php");
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Utilisateur';

include __DIR__ . '/includes/user-header.php';
?>

<div class="layout">
<?php include __DIR__ . '/includes/user-sidebar.php'; ?>
<main class="main">

    <header class="topbar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-user">
            <span>Bonjour, <?= htmlspecialchars($userName) ?></span>
            <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar">
        </div>
    </header>

    <section class="content">
        <h1>Mes réclamations</h1>

        <section class="section">
            <div class="section-header">
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-list"></i> Toutes
                    </button>
                    <button class="filter-btn" data-filter="pending">
                        <i class="fas fa-clock"></i> En attente
                    </button>
                    <button class="filter-btn" data-filter="in-progress">
                        <i class="fas fa-hourglass-half"></i> En cours
                    </button>
                    <button class="filter-btn" data-filter="resolved">
                        <i class="fas fa-check-circle"></i> Résolues
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#REC-2025-001</td>
                            <td>Produit défectueux reçu</td>
                            <td>Produit</td>
                            <td>01/11/2025</td>
                            <td><span class="priority-badge priority-high">Haute</span></td>
                            <td><span class="badge badge-success">Résolue</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-002</td>
                            <td>Service client insatisfaisant</td>
                            <td>Service</td>
                            <td>02/11/2025</td>
                            <td><span class="priority-badge priority-medium">Moyenne</span></td>
                            <td><span class="badge badge-info">En cours</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-003</td>
                            <td>Livraison en retard de 5 jours</td>
                            <td>Livraison</td>
                            <td>03/11/2025</td>
                            <td><span class="priority-badge priority-urgent">Urgente</span></td>
                            <td><span class="badge badge-warning">En attente</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-004</td>
                            <td>Facture incorrecte</td>
                            <td>Facturation</td>
                            <td>04/11/2025</td>
                            <td><span class="priority-badge priority-low">Basse</span></td>
                            <td><span class="badge badge-success">Résolue</span></td>
                            <td>
                                <a href="reclamation-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>

</main>
</div>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
<?php include __DIR__ . '/includes/user-footer.php'; ?>
