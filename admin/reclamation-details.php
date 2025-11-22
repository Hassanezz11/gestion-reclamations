<?php
session_start();

$page_title  = "Détail Réclamation";
$active_menu = "reclamations";

include 'includes/admin-header.php';
?>

<div class="layout">
    <?php include 'includes/admin-sidebar.php'; ?>

    <main class="main">

        <header class="topbar">
            <div>
                <h1>Détail de la Réclamation #1562</h1>
                <p class="topbar-subtitle">Consultez toutes les informations liées à cette réclamation.</p>
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
                <!-- Infos principales -->
                <div class="card">
                    <div class="card-header">
                        <h2>Informations principales</h2>
                    </div>

                    <p><strong>Client :</strong> Salma</p>
                    <p><strong>Email :</strong> salma@example.com</p>
                    <p><strong>Objet :</strong> Facture erronée</p>
                    <p><strong>Catégorie :</strong> Facturation / Erreur</p>
                    <p><strong>Priorité :</strong> Haute</p>
                    <p><strong>Statut :</strong> <span class="badge badge-warning">En cours</span></p>
                    <p><strong>Agent assigné :</strong> Youssef</p>

                    <p><strong>Description :</strong></p>
                    <p>
                        Le montant de la facture reçue ne correspond pas au devis signé.
                        Le client demande vérification et correction.
                    </p>
                </div>

                <!-- Pièces jointes & actions -->
                <div class="card">
                    <div class="card-header">
                        <h2>Pièces jointes & actions</h2>
                    </div>

                    <ul class="simple-list">
                        <li><a href="#">facture_client.pdf</a></li>
                        <li><a href="#">devis_initial.pdf</a></li>
                    </ul>

                    <div class="mt-1">
                        <button class="btn btn-primary btn-sm" onclick="window.location.href='assign-agent.php'">
                            <i class="fas fa-user-plus"></i>&nbsp; Modifier l’agent
                        </button>
                        <button class="btn btn-secondary btn-sm">
                            <i class="fas fa-external-link-alt"></i>&nbsp; Ouvrir dans la gestion
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="card mt-2">
                <div class="card-header">
                    <h2>Messages client / agent</h2>
                </div>

                <div class="messages">
                    <div class="message message-user">
                        <p>Bonjour, le montant de la facture ne correspond pas à ce qui était prévu.</p>
                        <span class="message-meta">Client • 12/11/2025</span>
                    </div>
                    <div class="message message-agent">
                        <p>Bonjour, nous vérifions et revenons vers vous rapidement.</p>
                        <span class="message-meta">Agent Youssef • 13/11/2025</span>
                    </div>
                </div>
            </div>

            <!-- Remarques internes -->
            <div class="card mt-2">
                <div class="card-header">
                    <h2>Remarques internes</h2>
                </div>

                <ul class="timeline">
                    <li><strong>Agent Youssef</strong> – Client contacté, demande copie du devis <span>13/11/2025</span></li>
                    <li><strong>Admin</strong> – Affectation de la réclamation à Youssef <span>12/11/2025</span></li>
                </ul>
            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
