<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Reclamation.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/SubCategory.php';

class ReclamationController
{
    private PDO $pdo;
    private Reclamation $reclamationModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
        $this->reclamationModel = new Reclamation($this->pdo);
        $this->categoryModel = new Category($this->pdo);
    }

    public function create(int $userId): array
    {
        $objet = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categorieId = (int)($_POST['category'] ?? 0);
        $sousCategorieId = (int)($_POST['subcategory'] ?? 0);
        $priorite = trim($_POST['priority'] ?? 'moyenne');
        $reference = trim($_POST['reference'] ?? '');

        if (empty($objet) || empty($description)) {
            return ['success' => false, 'message' => "Le titre et la description sont obligatoires."];
        }

        if (strlen($objet) > 200) {
            return ['success' => false, 'message' => "Le titre ne peut pas dépasser 200 caractères."];
        }

        if (!in_array($priorite, ['basse', 'moyenne', 'haute', 'urgente'])) {
            $priorite = 'moyenne';
        }

        if ($categorieId <= 0) {
            return ['success' => false, 'message' => "Veuillez sélectionner une catégorie."];
        }

        if ($sousCategorieId <= 0) {
            return ['success' => false, 'message' => "Veuillez sélectionner une sous-catégorie."];
        }

        $pieceJointe = $this->handleFileUpload();

        try {
            $result = $this->reclamationModel->create(
                $userId,
                $categorieId,
                $sousCategorieId,
                $objet,
                $description,
                $pieceJointe,
                $priorite
            );

            if ($result) {
                return ['success' => true, 'message' => 'Réclamation créée avec succès.'];
            }

            return ['success' => false, 'message' => 'Erreur lors de la création de la réclamation.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()];
        }
    }

    public function getAllCategories(): array
    {
        return $this->categoryModel->getAll();
    }

    public function getSubcategoriesByCategoryId(int $categoryId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sous_categories WHERE categorie_id = ? ORDER BY nom");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    private function handleFileUpload(): ?string
    {
        if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        $fileType = $_FILES['attachment']['type'];
        $fileSize = $_FILES['attachment']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            return null;
        }

        if ($fileSize > $maxSize) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../uploads/reclamations/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('rec_') . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadPath)) {
            return $filename;
        }

        return null;
    }
}
?>
