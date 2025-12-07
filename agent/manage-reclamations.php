<?php
session_start();
$page_title  = "Gérer les réclamations";
$active_menu = "reclamations";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'agent') {
    header("Location: /auth/login.php");
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Agent';

include __DIR__ . '/includes/agent-header.php';
?>

<div class="layout">
<?php include __DIR__ . '/includes/agent-sidebar.php'; ?>
<main class="main">

    <header class="top-bar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="top-bar-right">
            <div class="user-info">
                <span>Bonjour, <?= htmlspecialchars($userName) ?></span>
                <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar">
            </div>
        </div>
    </header>

    <section class="content">
        <h1>Gestion des réclamations</h1>

        <section class="section">
            <div class="section-header">
                <div class="search-bar">
                    <input type="text" placeholder="Rechercher par ID, client...">
                    <button class="btn btn-secondary btn-icon">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">Toutes</button>
                    <button class="filter-btn" data-filter="pending">En attente</button>
                    <button class="filter-btn" data-filter="in-progress">En cours</button>
                    <button class="filter-btn" data-filter="resolved">Résolues</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#REC-2025-005</td>
                            <td>Sophie Martin</td>
                            <td>Service</td>
                            <td>01/11/2025</td>
                            <td><span class="priority-badge priority-urgent">Urgente</span></td>
                            <td><span class="badge badge-warning">En attente</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Traiter</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-006</td>
                            <td>Paul Moreau</td>
                            <td>Livraison</td>
                            <td>02/11/2025</td>
                            <td><span class="priority-badge priority-high">Haute</span></td>
                            <td><span class="badge badge-info">En cours</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Détails</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-007</td>
                            <td>Claire Duval</td>
                            <td>Produit</td>
                            <td>03/11/2025</td>
                            <td><span class="priority-badge priority-medium">Moyenne</span></td>
                            <td><span class="badge badge-warning">En attente</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Traiter</a>
                            </td>
                        </tr>
                        <tr>
                            <td>#REC-2025-008</td>
                            <td>Marc Laurent</td>
                            <td>Facturation</td>
                            <td>04/11/2025</td>
                            <td><span class="priority-badge priority-low">Basse</span></td>
                            <td><span class="badge badge-success">Résolue</span></td>
                            <td>
                                <a href="reclamation-agent-details.php" class="btn btn-sm btn-secondary">Détails</a>
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
<?php include __DIR__ . '/includes/agent-footer.php'; ?>
