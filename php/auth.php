<?php
/**
 * Auth Class
 * ----------
 * Gère l’authentification (login, register, logout)
 * + Vérification des rôles
 * + Redirections automatiques
 */

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/models/User.php';

class Auth
{
    // Base URL path of the app; empty because the project now lives at the vhost root (http://reclam/)
    public const BASE_PATH = '';
    private PDO $pdo;
    private User $userModel;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
        $this->userModel = new User($this->pdo);

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /* =====================================
       LOGIN
       ===================================== */
    public function login(string $email, string $password): bool
    {
        if ($email === '' || $password === '') {
            $_SESSION['auth_error'] = "Veuillez remplir tous les champs.";
            return false;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['auth_error'] = "Aucun compte trouvé avec cet email.";
            return false;
        }

        if (!password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['auth_error'] = "Mot de passe incorrect.";
            return false;
        }

        // Successful login
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom_complet'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        $this->redirectDashboard($user['role']);
        return true;
    }

    /* =====================================
       REGISTER
       ===================================== */
    public function register(string $nom, string $email, string $password, string $confirm): bool
    {
        if ($nom === '' || $email === '' || $password === '' || $confirm === '') {
            $_SESSION['auth_error'] = "Veuillez remplir tous les champs.";
            return false;
        }

        if ($password !== $confirm) {
            $_SESSION['auth_error'] = "Les mots de passe ne correspondent pas.";
            return false;
        }

        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['auth_error'] = "Un compte existe déjà avec cet email.";
            return false;
        }

        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Create new user (role = user by default)
        $this->userModel->create($nom, $email, $hash, 'user');

        $_SESSION['auth_success'] = "Compte créé avec succès. Vous pouvez vous connecter.";
        return true;
    }

    /* =====================================
       LOGOUT
       ===================================== */
    public function logout()
    {
        session_destroy();
        header("Location: " . self::BASE_PATH . "/auth/login.php");
        exit;
    }

    /* =====================================
       REDIRECTION DASHBOARD SELON ROLE
       ===================================== */
    public function redirectDashboard(string $role)
    {
        switch ($role) {
            case 'admin':
                header("Location: " . self::BASE_PATH . "/admin/admin-dashboard.php");
                break;

            case 'agent':
                header("Location: " . self::BASE_PATH . "/agent/agent-dashboard.php");
                break;

            case 'user':
                header("Location: " . self::BASE_PATH . "/user/user-dashboard.php");
                break;

            default:
                header("Location: " . self::BASE_PATH . "/auth/login.php");
        }
        exit;
    }

    /* =====================================
       Middleware-like: Require User Logged
       ===================================== */
    public static function requireLogin(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header("Location: " . self::BASE_PATH . "/auth/login.php");
            exit;
        }
    }

    /* =====================================
       Require specific role
       ===================================== */
    public static function requireRole(string $role): void
    {
        self::requireLogin();

        if ($_SESSION['user_role'] !== $role) {
            header("Location: " . self::BASE_PATH . "/admin/unauthorized.php");
            exit;
        }
    }
}

/**
 * Front controller : traite les requÇôtes POST envoyÇûes directement vers /php/auth.php
 */
if (php_sapi_name() !== 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $action = $_GET['action'] ?? '';

    // On exige un POST pour manipuler l'authentification
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Location: ' . Auth::BASE_PATH . '/auth/login.php');
        exit;
    }

    $auth = new Auth();

    switch ($action) {
        case 'login':
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Auth::login gÉùre sa redirection en cas de succÇùs
            $auth->login($email, $password);
            header('Location: ' . Auth::BASE_PATH . '/auth/login.php');
            exit;

        case 'register':
            $nom = trim($_POST['nom_complet'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            $success = $auth->register($nom, $email, $password, $confirm);
            header('Location: ' . ($success ? Auth::BASE_PATH . '/auth/login.php' : Auth::BASE_PATH . '/auth/register.php'));
            exit;

        default:
            header('Location: ' . Auth::BASE_PATH . '/auth/login.php');
            exit;
    }
}
?>
