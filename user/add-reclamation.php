<?php
session_start();
require_once __DIR__ . '/../php/config.php';
$page_title  = "Nouvelle réclamation";
$active_menu = "add";

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
        <h1>Soumettre une nouvelle réclamation</h1>

        <section class="section">
            <form class="form-large" id="reclamationForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Catégorie *</label>
                        <select id="category" name="category" required onchange="updateSubcategories()">
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="product">Produit</option>
                            <option value="service">Service</option>
                            <option value="delivery">Livraison</option>
                            <option value="billing">Facturation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory">Sous-catégorie *</label>
                        <select id="subcategory" name="subcategory" required>
                            <option value="">Sélectionnez d'abord une catégorie</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="priority">Priorité *</label>
                        <select id="priority" name="priority" required>
                            <option value="low">Basse</option>
                            <option value="medium" selected>Moyenne</option>
                            <option value="high">Haute</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reference">Numéro de référence (optionnel)</label>
                        <input type="text" id="reference" name="reference" placeholder="Ex: #CMD-123456">
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Titre *</label>
                    <input type="text" id="title" name="title" required placeholder="Résumé de votre réclamation">
                </div>

                <div class="form-group">
                    <label for="description">Description détaillée *</label>
                    <textarea id="description" name="description" required placeholder="Décrivez votre réclamation en détail..."></textarea>
                </div>

                <div class="form-group">
                    <label for="attachment">Pièce jointe (optionnelle)</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <span class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Cliquez ou glissez-déposez un fichier
                        </span>
                    </div>
                    <small>Formats acceptés: PDF, JPG, PNG, DOC, DOCX (Max 5MB)</small>
                </div>

                <div class="form-actions">
                    <a href="user-dashboard.php" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Soumettre la réclamation</button>
                </div>
            </form>
        </section>
    </section>

</main>
</div>

<script>
    const subcategories = {
        'product': ['Produit défectueux', 'Produit endommagé', 'Produit non conforme'],
        'service': ['Service insatisfaisant', 'Délai excessif', 'Manque de professionnalisme'],
        'delivery': ['Livraison en retard', 'Produit non reçu', 'Mauvaise adresse'],
        'billing': ['Facturation erronée', 'Double facturation', 'Remboursement']
    };

    function updateSubcategories() {
        const category = document.getElementById('category').value;
        const subcategorySelect = document.getElementById('subcategory');
        
        subcategorySelect.innerHTML = '<option value=\"\">Sélectionnez une sous-catégorie</option>';
        
        if (category && subcategories[category]) {
            subcategories[category].forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.toLowerCase().replace(/\\s+/g, '-');
                option.textContent = sub;
                subcategorySelect.appendChild(option);
            });
        }
    }

    document.getElementById('reclamationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Réclamation soumise avec succès!');
        window.location.href = 'my-reclamations.php';
    });
</script>
<?php include __DIR__ . '/includes/user-footer.php'; ?>
