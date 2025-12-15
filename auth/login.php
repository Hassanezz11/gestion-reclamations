<?php
session_start();
require_once __DIR__ . '/../php/config.php';

$error = $_SESSION['auth_error'] ?? null;
$success = $_SESSION['auth_success'] ?? null;
unset($_SESSION['auth_error'], $_SESSION['auth_success']);

$actionUrl = app_url('php/auth.php?action=login');
$registerUrl = app_url('auth/register.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Gestion des Réclamations</title>
  <link rel="stylesheet" href="<?= app_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= app_url('assets/css/auth.css') ?>">

</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h1>Se connecter</h1>
        <p>Accédez à votre espace de gestion des réclamations.</p>
      </div>

      <?php if ($error): ?>
        <div class="auth-alert auth-alert-error">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="auth-alert auth-alert-success">
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <form class="auth-form" method="post" action="<?= htmlspecialchars($actionUrl) ?>">
        <div class="form-group">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" required placeholder="exemple@domaine.com">
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" required placeholder="Votre mot de passe">
        </div>

        <button type="submit" class="btn btn-primary btn-full">Connexion</button>
      </form>

      <div class="auth-footer-text">
        <span>Pas encore de compte ?</span>
        <a href="<?= htmlspecialchars($registerUrl) ?>">Créer un compte</a>
      </div>
    </div>
  </div>
</body>
</html>
