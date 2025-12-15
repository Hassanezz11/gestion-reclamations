<?php
require_once __DIR__ . '/../php/auth.php';
Auth::requireRole('admin');

$page_title  = "Gestion des Catégories";
$active_menu = "categories";

require_once __DIR__ . '/../php/database.php';
require_once __DIR__ . '/../php/models/Category.php';
require_once __DIR__ . '/../php/models/SubCategory.php';

$pdo = Database::getInstance();
$catModel = new Category($pdo);
$subModel = new SubCategory($pdo);

$success = $error = "";
$selectedCat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

/* ===========================
   CATEGORY CREATION
   =========================== */
if (isset($_POST['add_category'])) {
    $nom = trim($_POST['nom']);
    $desc = trim($_POST['description'] ?? "");

    if ($nom === "") {
        $error = "Le nom de la catégorie est obligatoire.";
    } else {
        if ($catModel->create($nom, $desc)) {
            $success = "Catégorie ajoutée !";
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    }
}

/* ===========================
   CATEGORY DELETE
   =========================== */
if (isset($_GET['delete_cat'])) {
    $id = (int)$_GET['delete_cat'];
    $catModel->delete($id);
    header("Location: manage-categories.php?success=1");
    exit;
}

/* ===========================
   SUBCATEGORY CREATION
   =========================== */
if (isset($_POST['add_subcat'])) {
    $nom = trim($_POST['sub_nom']);
    $catId = (int)$_POST['categorie_id'];

    if ($nom === "" || $catId <= 0) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $subModel->create($catId, $nom);
        $success = "Sous-catégorie ajoutée.";
    }
}

/* ===========================
   SUBCATEGORY DELETE
   =========================== */
if (isset($_GET['delete_subcat'])) {
    $id = (int)$_GET['delete_subcat'];
    $subModel->delete($id);
    header("Location: manage-categories.php?cat=$selectedCat");
    exit;
}

$categories = $catModel->getAll();
$subCategories = $selectedCat ? $subModel->getByCategory($selectedCat) : [];

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
        </header>

        <section class="content">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>


            <div class="grid-2-columns">

                <!-- ================= CAT LIST ================= -->
                <div class="card">
                    <div class="card-header"><h2>Catégories</h2></div>

                    <ul class="simple-list">

                        <?php foreach ($categories as $c): ?>
                            <li>
                                <a href="manage-categories.php?cat=<?= $c['id'] ?>">
                                    <strong><?= htmlspecialchars($c['nom']) ?></strong>
                                </a>

                                <span class="list-actions">
                                    <a class="btn btn-danger btn-sm"
                                       onclick="return confirm('Supprimer cette catégorie ?');"
                                       href="manage-categories.php?delete_cat=<?= $c['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </span>
                            </li>
                        <?php endforeach; ?>

                    </ul>

                    <form class="mt-1" method="POST">
                        <div class="form-group">
                            <label>Nouvelle catégorie</label>
                            <input type="text" class="input-text" name="nom">
                        </div>

                        <div class="form-group">
                            <label>Description (optionnel)</label>
                            <input type="text" class="input-text" name="description">
                        </div>

                        <button class="btn btn-primary btn-sm" name="add_category">
                            <i class="fas fa-plus"></i>&nbsp; Ajouter
                        </button>
                    </form>
                </div>

                <!-- ================= SUB CATEGORY ================= -->
                <div class="card">
                    <div class="card-header"><h2>Sous-catégories</h2></div>

                    <form method="GET" class="form-group">
                        <label>Choisir catégorie</label>
                        <select class="input-select" name="cat" onchange="this.form.submit()">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"
                                    <?= $selectedCat == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>

                    <?php if ($selectedCat): ?>

                        <ul class="simple-list mt-1">
                            <?php foreach ($subCategories as $sc): ?>
                                <li>
                                    <span><?= htmlspecialchars($sc['nom']) ?></span>
                                    <span class="list-actions">
                                        <a class="btn btn-danger btn-sm"
                                           onclick="return confirm('Supprimer cette sous-catégorie ?');"
                                           href="manage-categories.php?delete_subcat=<?= $sc['id'] ?>&cat=<?= $selectedCat ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <form class="mt-1" method="POST">
                            <input type="hidden" name="categorie_id" value="<?= $selectedCat ?>">

                            <div class="form-group">
                                <label>Nouvelle sous-catégorie</label>
                                <input type="text" class="input-text" name="sub_nom">
                            </div>

                            <button class="btn btn-primary btn-sm" name="add_subcat">
                                <i class="fas fa-plus"></i> Ajouter
                            </button>
                        </form>

                    <?php else: ?>

                        <p class="text-muted">Sélectionnez une catégorie pour voir ses sous-catégories.</p>

                    <?php endif; ?>
                </div>

            </div>

        </section>

    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>
