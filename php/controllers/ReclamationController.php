<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Reclamation.php';

class ReclamationController
{
    public function create()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /auth/login.php');
            exit;
        }

        // Récupérer les données du formulaire (à sécuriser)
        $data = [
            'utilisateur_id'   => (int)$userId,
            'categorie_id'     => (int)($_POST['categorie_id'] ?? 0),
            'sous_categorie_id'=> (int)($_POST['sous_categorie_id'] ?? 0),
            'objet'            => $_POST['objet'] ?? '',
            'description'      => $_POST['description'] ?? '',
            'piece_jointe'     => '', // à gérer via upload
            'priorite'         => $_POST['priorite'] ?? 'Moyenne',
        ];

        $model = new Reclamation(Database::getConnection());
        $model->create($data);

        header('Location: /user/my-reclamations.php');
        exit;
    }
}
?>
