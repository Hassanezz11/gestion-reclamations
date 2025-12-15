<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Reclamation.php';

class MessageController
{
    public function send()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $userRole = $_SESSION['user_role'] ?? '';

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non authentifié']);
            exit;
        }

        $reclamationId = (int)($_POST['reclamation_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if ($reclamationId === 0 || empty($message)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }

        // Verify user has access to this reclamation
        $reclamationModel = new Reclamation(Database::getInstance());
        $reclamation = $reclamationModel->getById($reclamationId);

        if (!$reclamation) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Réclamation non trouvée']);
            exit;
        }

        // Check if user is the owner (for users) or assigned agent (for agents)
        $hasAccess = false;
        if ($userRole === 'user' && $reclamation['utilisateur_id'] == $userId) {
            $hasAccess = true;
        } elseif ($userRole === 'agent') {
            // Check if agent is assigned to this reclamation
            require_once __DIR__ . '/../models/Affectation.php';
            $affectationModel = new Affectation(Database::getInstance());
            $affectation = $affectationModel->getByReclamationAndAgent($reclamationId, $userId);
            $hasAccess = ($affectation !== null);
        } elseif ($userRole === 'admin') {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            exit;
        }

        // Send message
        $messageModel = new Message(Database::getInstance());
        $success = $messageModel->send($reclamationId, $userId, $message);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Message envoyé avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi du message']);
        }
    }

    public function getByReclamation($reclamationId)
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $userRole = $_SESSION['user_role'] ?? '';

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Non authentifié']);
            exit;
        }

        // Verify access
        $reclamationModel = new Reclamation(Database::getInstance());
        $reclamation = $reclamationModel->getById($reclamationId);

        if (!$reclamation) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Réclamation non trouvée']);
            exit;
        }

        $hasAccess = false;
        if ($userRole === 'user' && $reclamation['utilisateur_id'] == $userId) {
            $hasAccess = true;
        } elseif ($userRole === 'agent') {
            require_once __DIR__ . '/../models/Affectation.php';
            $affectationModel = new Affectation(Database::getInstance());
            $affectation = $affectationModel->getByReclamationAndAgent($reclamationId, $userId);
            $hasAccess = ($affectation !== null);
        } elseif ($userRole === 'admin') {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            exit;
        }

        $messageModel = new Message(Database::getInstance());
        $messages = $messageModel->getByReclamation($reclamationId);

        echo json_encode(['success' => true, 'messages' => $messages]);
    }
}

// Handle AJAX requests
if (php_sapi_name() !== 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $action = $_GET['action'] ?? '';

    $controller = new MessageController();

    switch ($action) {
        case 'send':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');
                $controller->send();
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            }
            break;

        case 'get':
            $reclamationId = (int)($_GET['reclamation_id'] ?? 0);
            if ($reclamationId > 0) {
                header('Content-Type: application/json');
                $controller->getByReclamation($reclamationId);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID de réclamation requis']);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Action non trouvée']);
    }
}
?>
