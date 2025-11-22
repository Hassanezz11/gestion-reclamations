<?php
session_start();
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/models/User.php';

$action = $_GET['action'] ?? null;

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['auth_error'] = 'Veuillez remplir tous les champs.';
        header('Location: /auth/login.php');
        exit;
    }

    $userModel = new User(Database::getConnection());
    $user = $userModel->findByEmail($email);

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom_complet'];
        $_SESSION['user_role'] = $user['role']; // 'admin','agent','user'
        header('Location: /index.php');
        exit;
    } else {
        $_SESSION['auth_error'] = 'Identifiants incorrects.';
        header('Location: /auth/login.php');
        exit;
    }
}

if ($action === 'register') {
    $nom = trim($_POST['nom_complet'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($nom === '' || $email === '' || $password === '' || $confirm === '') {
        $_SESSION['auth_error'] = 'Veuillez remplir tous les champs.';
        header('Location: /auth/register.php');
        exit;
    }
    if ($password !== $confirm) {
        $_SESSION['auth_error'] = 'Les mots de passe ne correspondent pas.';
        header('Location: /auth/register.php');
        exit;
    }

    $userModel = new User(Database::getConnection());
    $existing = $userModel->findByEmail($email);
    if ($existing) {
        $_SESSION['auth_error'] = 'Un compte existe déjà avec cet e-mail.';
        header('Location: /auth/register.php');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $userModel->create($nom, $email, $hash, 'user');

    $_SESSION['auth_success'] = 'Compte créé, vous pouvez maintenant vous connecter.';
    header('Location: /auth/register.php');
    exit;
}

header('Location: /auth/login.php');
exit;
?>
