<?php
session_start();
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/../php/controllers/UserController.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    redirect_to('auth/login.php');
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('user/my-reclamations.php');
}

$reclamationId = (int)($_POST['reclamation_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($reclamationId <= 0 || empty($message)) {
    $_SESSION['error'] = 'Données invalides';
    redirect_to('user/reclamation-details.php?id=' . $reclamationId);
    exit;
}

$userController = new UserController();
$reclamation = $userController->getReclamationDetails($reclamationId, $userId);

if (!$reclamation) {
    $_SESSION['error'] = 'Réclamation introuvable';
    redirect_to('user/my-reclamations.php');
    exit;
}

$result = $userController->sendMessage($reclamationId, $userId, $message);

if ($result) {
    $_SESSION['success'] = 'Message envoyé avec succès';
} else {
    $_SESSION['error'] = 'Erreur lors de l\'envoi du message';
}

redirect_to('user/reclamation-details.php?id=' . $reclamationId);
?>
