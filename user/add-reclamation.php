<?php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/../php/controllers/ReclamationController.php';

$page_title  = "Nouvelle réclamation";
$active_menu = "add";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
}

$userName = $_SESSION['user_name'] ?? 'Utilisateur';
$userId = $_SESSION['user_id'];

$reclamationController = new ReclamationController();
$categories = $reclamationController->getAllCategories();

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $reclamationController->create($userId);
    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        redirect_to('user/my-reclamations.php');
        exit;
    } else {
        $error = $result['message'];
    }
}

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

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <section class="section">
            <form class="form-large" method="POST" action="" enctype="multipart/form-data" id="reclamationForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Catégorie *</label>
                        <select id="category" name="category" required onchange="updateSubcategories()">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                            <?php endforeach; ?>
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
                            <option value="basse">Basse</option>
                            <option value="moyenne" selected>Moyenne</option>
                            <option value="haute">Haute</option>
                            <option value="urgente">Urgente</option>
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
    function updateSubcategories() {
        const categoryId = document.getElementById('category').value;
        const subcategorySelect = document.getElementById('subcategory');

        subcategorySelect.innerHTML = '<option value="">Chargement...</option>';

        if (!categoryId) {
            subcategorySelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie</option>';
            return;
        }

        fetch('../php/api/get-subcategories.php?category_id=' + categoryId)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>';
                data.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.nom;
                    subcategorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                subcategorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }
</script>
<?php include __DIR__ . '/includes/user-footer.php'; ?>
