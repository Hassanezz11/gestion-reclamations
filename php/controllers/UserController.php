<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Reclamation.php';

class UserController
{
    public function listReclamations()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /auth/login.php');
            exit;
        }

        $model = new Reclamation(Database::getConnection());
        $reclamations = $model->findByUser((int)$userId);

        // Ici, vous pouvez inclure un template et lui passer $reclamations
        require __DIR__ . '/../../user/my-reclamations.php';
    }
}
?>
