<?php
session_start();
require_once __DIR__ . '/../php/config.php';
$page_title  = "Détails de la réclamation";
$active_menu = "list";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
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
        <a href="my-reclamations.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour aux réclamations
        </a>

        <div class="details-container">
            <section class="section">
                <div class="section-header">
                    <h1>Réclamation #REC-2025-002</h1>
                    <span class="badge badge-info">En cours</span>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <label>Catégorie</label>
                        <p>Service</p>
                    </div>
                    <div class="detail-item">
                        <label>Sous-catégorie</label>
                        <p>Service insatisfaisant</p>
                    </div>
                    <div class="detail-item">
                        <label>Date de soumission</label>
                        <p>02/11/2025</p>
                    </div>
                    <div class="detail-item">
                        <label>Priorité</label>
                        <p><span class="priority-badge priority-medium">Moyenne</span></p>
                    </div>
                </div>

                <hr>

                <h3>Description</h3>
                <p>J'ai appelé le service client le 01/11/2025 pour une question sur ma commande. L'agent m'a mis en attente pendant 15 minutes puis m'a raccroché au nez. J'ai rappelé et j'ai dû expliquer à nouveau le problème. C'est très frustrant.</p>

                <hr>

                <h3>Pièce jointe</h3>
                <div class="attachment">
                    <i class="fas fa-file-pdf"></i>
                    <span>preuve_appel.pdf</span>
                    <a href="#" class="btn btn-sm btn-secondary">Télécharger</a>
                </div>
            </section>

            <section class="section">
                <h2>Historique et remarques</h2>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Réclamation soumise</h4>
                            <p class="timestamp">02/11/2025 à 14:30</p>
                            <p>Votre réclamation a été enregistrée avec succès.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Assignée à un agent</h4>
                            <p class="timestamp">02/11/2025 à 15:00</p>
                            <p>Assignée à Marie Leclerc - Service Client</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Remarque d'agent</h4>
                            <p class="timestamp">03/11/2025 à 10:15</p>
                            <p>J'ai vérifié les enregistrements d'appel. Confirmé: déconnexion accidentelle. Je vous propose un bon d'achat de 50€ en compensation.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2>Messages</h2>
                
                <div class="chat-container">
                    <div class="message-group">
                        <div class="message message-other">
                            <div class="message-sender">Marie Leclerc</div>
                            <p>Bonjour, merci de nous avoir contactés. J'ai bien reçu votre réclamation.</p>
                            <span class="message-time">02/11/2025 15:05</span>
                        </div>

                        <div class="message message-other">
                            <div class="message-sender">Marie Leclerc</div>
                            <p>Après vérification, je confirme que l'appel a été coupé de notre côté. Mes excuses.</p>
                            <span class="message-time">03/11/2025 10:20</span>
                        </div>

                        <div class="message message-self">
                            <p>Merci pour votre réponse rapide. Je suis satisfait de la compensation proposée.</p>
                            <span class="message-time">03/11/2025 11:30</span>
                        </div>
                    </div>

                    <form class="message-form" id="messageForm">
                        <input type="text" id="messageInput" placeholder="Votre message..." required>
                        <button type="submit" class="btn btn-primary btn-icon">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </section>

</main>
</div>

<script>
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const message = input.value;
        
        if (message.trim()) {
            alert('Message envoyé avec succès!');
            input.value = '';
        }
    });
</script>
<?php include __DIR__ . '/includes/user-footer.php'; ?>
