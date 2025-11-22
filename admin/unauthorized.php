<?php
session_start();

$page_title = "Accès non autorisé";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-body">

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-lock"></i> Accès non autorisé</h1>
            <p>Vous n’avez pas les droits nécessaires pour accéder à cette page.</p>
        </div>

        <div style="text-align:center; margin-top:1rem;">
            <a href="../index.php" class="btn btn-primary btn-full">
                <i class="fas fa-home"></i>&nbsp; Retour au tableau de bord
            </a>
        </div>
    </div>
</div>

</body>
</html>
