<?php
session_start();
$error = $_SESSION['auth_error'] ?? null;
$success = $_SESSION['auth_success'] ?? null;
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription - Gestion des Réclamations</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h1>Créer un compte</h1>
        <p>Inscrivez-vous pour soumettre et suivre vos réclamations.</p>
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

      <form class="auth-form" method="post" action="/gestion-reclamations/php/auth.php?action=register">
        <div class="form-group">
          <label for="nom_complet">Nom complet</label>
          <input type="text" id="nom_complet" name="nom_complet" required placeholder="Votre nom et prénom">
        </div>

        <div class="form-group">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" required placeholder="exemple@domaine.com">
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirmer le mot de passe</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Créer mon compte</button>
      </form>

      <div class="auth-footer-text">
        <span>Déjà un compte ?</span>
        <a href="/gestion-reclamations/auth/login.php">Se connecter</a>
      </div>
    </div>
  </div>
</body>
</html>
