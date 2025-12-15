<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Reclamation.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/SubCategory.php';

class ReclamationController
{
    private const UPLOAD_DIR = __DIR__ . '/../../uploads/';
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

    public function create()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId || ($_SESSION['user_role'] ?? '') !== 'user') {
            header('Location: /auth/login.php');
            exit;
        }

        $error = '';
        $pieceJointe = null;

        // Validate required fields
        $categorieId = (int)($_POST['categorie_id'] ?? 0);
        $sousCategorieId = (int)($_POST['sous_categorie_id'] ?? 0);
        $objet = trim($_POST['objet'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priorite = $_POST['priorite'] ?? 'moyenne';

        if (empty($objet) || empty($description) || $categorieId === 0 || $sousCategorieId === 0) {
            $error = "Veuillez remplir tous les champs obligatoires.";
        }

        // Handle file upload
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['attachment'];
            
            // Validate file size
            if ($file['size'] > self::MAX_FILE_SIZE) {
                $error = "Le fichier est trop volumineux (max 5MB).";
            } else {
                // Validate file extension
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, self::ALLOWED_EXTENSIONS)) {
                    $error = "Format de fichier non autorisé. Formats acceptés: " . implode(', ', self::ALLOWED_EXTENSIONS);
                } else {
                    // Create uploads directory if it doesn't exist
                    if (!is_dir(self::UPLOAD_DIR)) {
                        mkdir(self::UPLOAD_DIR, 0755, true);
                    }

                    // Generate unique filename
                    $filename = uniqid('rec_', true) . '_' . time() . '.' . $ext;
                    $filepath = self::UPLOAD_DIR . $filename;

                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        $pieceJointe = $filename;
                    } else {
                        $error = "Erreur lors du téléchargement du fichier.";
                    }
                }
            }
        } elseif (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
            $error = "Erreur lors du téléchargement du fichier.";
        }

        if ($error) {
            $_SESSION['reclamation_error'] = $error;
            header('Location: /user/add-reclamation.php');
            exit;
        }

        // Normalize priority
        $prioriteMap = ['low' => 'faible', 'medium' => 'moyenne', 'high' => 'haute', 'urgent' => 'haute'];
        $priorite = $prioriteMap[$priorite] ?? 'moyenne';

        // Create reclamation
        $model = new Reclamation(Database::getInstance());
        $success = $model->create($userId, $categorieId, $sousCategorieId, $objet, $description, $pieceJointe, $priorite);

        if ($success) {
            $_SESSION['reclamation_success'] = "Réclamation soumise avec succès!";
            header('Location: /user/my-reclamations.php');
        } else {
            $_SESSION['reclamation_error'] = "Erreur lors de la soumission de la réclamation.";
            header('Location: /user/add-reclamation.php');
        }
        exit;
    }

    public function getCategories()
    {
        $model = new Category(Database::getInstance());
        return $model->getAll();
    }

    public function getSubCategories($categoryId)
    {
        $model = new SubCategory(Database::getInstance());
        return $model->getByCategory($categoryId);
    }
}
?>
