<?php
session_start();
$page_title  = "Nouvelle réclamation";
$active_menu = "add";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header("Location: /auth/login.php");
    exit;
}

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Category.php';
require_once __DIR__ . '/../php/models/SubCategory.php';

$pdo = Database::getInstance();
$categoryModel = new Category($pdo);
$subCategoryModel = new SubCategory($pdo);

$categories = $categoryModel->getAll();

$userName = $_SESSION['user_name'] ?? 'Utilisateur';
$success = $_SESSION['reclamation_success'] ?? null;
$error = $_SESSION['reclamation_error'] ?? null;
unset($_SESSION['reclamation_success'], $_SESSION['reclamation_error']);

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
            <img src="../assets/images/logo.png" alt="Avatar" class="avatar">
        </div>
    </header>

    <section class="content">
        <h1>Soumettre une nouvelle réclamation</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section class="section">
            <form class="form-large" id="reclamationForm" method="POST" action="/php/controllers/ReclamationController.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie_id">Catégorie *</label>
                        <select id="categorie_id" name="categorie_id" required onchange="updateSubcategories()">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sous_categorie_id">Sous-catégorie *</label>
                        <select id="sous_categorie_id" name="sous_categorie_id" required>
                            <option value="">Sélectionnez d'abord une catégorie</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="priorite">Priorité *</label>
                        <select id="priorite" name="priorite" required>
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
                    <label for="objet">Titre *</label>
                    <input type="text" id="objet" name="objet" required placeholder="Résumé de votre réclamation">
                </div>

                <div class="form-group">
                    <label for="description">Description détaillée *</label>
                    <textarea id="description" name="description" required placeholder="Décrivez votre réclamation en détail..." rows="6"></textarea>
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
    const subcategories = <?= json_encode(array_reduce($categories, function($acc, $cat) use ($subCategoryModel) {
        $acc[$cat['id']] = $subCategoryModel->getByCategory($cat['id']);
        return $acc;
    }, [])) ?>;

    function updateSubcategories() {
        const categoryId = parseInt(document.getElementById('categorie_id').value);
        const subcategorySelect = document.getElementById('sous_categorie_id');
        
        subcategorySelect.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>';
        
        if (categoryId && subcategories[categoryId]) {
            subcategories[categoryId].forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.id;
                option.textContent = sub.nom;
                subcategorySelect.appendChild(option);
            });
        }
    }

    // File input styling
    const fileInput = document.getElementById('attachment');
    const fileLabel = document.querySelector('.file-input-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                fileLabel.innerHTML = '<i class="fas fa-file"></i> ' + e.target.files[0].name;
            } else {
                fileLabel.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Cliquez ou glissez-déposez un fichier';
            }
        });
    }
</script>
<?php include __DIR__ . '/includes/user-footer.php'; ?>
