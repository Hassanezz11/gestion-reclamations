<?php
session_start();
$page_title  = "Détails de la réclamation";
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
        <a href="manage-reclamations.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour aux réclamations
        </a>

        <div class="details-container">
            <section class="section">
                <div class="section-header">
                    <h1>Réclamation #REC-2025-005</h1>
                    <div class="status-actions">
                        <select id="statusSelect" class="form-control-inline">
                            <option value="pending">En attente</option>
                            <option value="in-progress" selected>En cours</option>
                            <option value="resolved">Résolue</option>
                            <option value="rejected">Rejetée</option>
                        </select>
                    </div>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <label>Client</label>
                        <p>Sophie Martin</p>
                    </div>
                    <div class="detail-item">
                        <label>Email</label>
                        <p>sophie.martin@email.com</p>
                    </div>
                    <div class="detail-item">
                        <label>Catégorie</label>
                        <p>Service</p>
                    </div>
                    <div class="detail-item">
                        <label>Priorité</label>
                        <p><span class="priority-badge priority-urgent">Urgente</span></p>
                    </div>
                </div>

                <hr>

                <h3>Description de la réclamation</h3>
                <p>J'ai attendu plus d'une heure pour être servi au comptoir. Le personnel avait l'air désorganisé et ne m'a pas offert d'explications. Très déçu par le service.</p>

                <hr>

                <h3>Pièce jointe</h3>
                <div class="attachment">
                    <i class="fas fa-file-pdf"></i>
                    <span>preuve_reclamation.pdf</span>
                    <a href="#" class="btn btn-sm btn-secondary">Télécharger</a>
                </div>
            </section>

            <section class="section">
                <h2>Remarques d'agent</h2>
                
                <form class="form-large" id="remarksForm">
                    <div class="form-group">
                        <label for="remarks">Ajouter une remarque</label>
                        <textarea id="remarks" name="remarks" placeholder="Vos remarques et observations..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enregistrer la remarque</button>
                    </div>
                </form>

                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Réclamation reçue</h4>
                            <p class="timestamp">01/11/2025 à 14:30</p>
                            <p>Réclamation enregistrée dans le système.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Remarque d'agent</h4>
                            <p class="timestamp">02/11/2025 à 10:00</p>
                            <p>J'ai vérifié les horaires du 01/11. Confirmé : personnel insuffisant ce jour.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2>Messages avec le client</h2>
                
                <div class="chat-container">
                    <div class="message-group">
                        <div class="message message-other">
                            <div class="message-sender">Sophie Martin (Client)</div>
                            <p>Bonjour, j'attends votre réponse sur ma réclamation.</p>
                            <span class="message-time">01/11/2025 15:00</span>
                        </div>

                        <div class="message message-self">
                            <div class="message-sender">Vous (Agent)</div>
                            <p>Bonjour Sophie, merci de nous avoir contactés. Je vais enquêter immédiatement.</p>
                            <span class="message-time">02/11/2025 09:30</span>
                        </div>

                        <div class="message message-self">
                            <div class="message-sender">Vous (Agent)</div>
                            <p>J'ai trouvé le problème. Nous proposons 50€ de compensation + excuses formelles.</p>
                            <span class="message-time">02/11/2025 10:15</span>
                        </div>

                        <div class="message message-other">
                            <div class="message-sender">Sophie Martin (Client)</div>
                            <p>Merci pour votre rapidité et votre aide!</p>
                            <span class="message-time">02/11/2025 11:00</span>
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
    document.getElementById('remarksForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Remarque enregistrée avec succès!');
        document.getElementById('remarks').value = '';
    });

    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        if (input.value.trim()) {
            alert('Message envoyé au client!');
            input.value = '';
        }
    });

    document.getElementById('statusSelect').addEventListener('change', function() {
        alert('Statut mis à jour: ' + this.value);
    });
</script>
<?php include __DIR__ . '/includes/agent-footer.php'; ?>
