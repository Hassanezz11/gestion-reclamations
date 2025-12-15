<?php
/**
 * Auth Class
 * ----------
 * Gère l’authentification (login, register, logout)
 * + Vérification des rôles
 * + Redirections automatiques
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/models/User.php';

class Auth
{
    // Base URL path detected at runtime (supports subfolder deployments)
    public const BASE_PATH = APP_BASE_PATH;
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
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        redirect_to('auth/login.php');
    }

    /* =====================================
       REDIRECTION DASHBOARD SELON ROLE
       ===================================== */
    public function redirectDashboard(string $role)
    {
        switch ($role) {
            case 'admin':
                redirect_to('admin/admin-dashboard.php');

            case 'agent':
                redirect_to('agent/agent-dashboard.php');

            case 'user':
                redirect_to('user/user-dashboard.php');

            default:
                redirect_to('auth/login.php');
        }
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
            redirect_to('auth/login.php');
        }
    }

    /* =====================================
       Require specific role
       ===================================== */
    public static function requireRole(string $role): void
    {
        self::requireLogin();

        if ($_SESSION['user_role'] !== $role) {
            redirect_to('admin/unauthorized.php');
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
        redirect_to('auth/login.php');
    }

    $auth = new Auth();

    switch ($action) {
        case 'login':
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Auth::login gÉùre sa redirection en cas de succÇùs
            $auth->login($email, $password);
            redirect_to('auth/login.php');

        case 'register':
            $nom = trim($_POST['nom_complet'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            $success = $auth->register($nom, $email, $password, $confirm);
            if ($success) {
                redirect_to('auth/login.php');
            }
            redirect_to('auth/register.php');

        default:
            redirect_to('auth/login.php');
    }
}
?>
