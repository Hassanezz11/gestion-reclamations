<?php
session_start();

$page_title = "Accès Non Autorisé";
$active_menu = "";

// If user is not logged in → use simple layout
$isLogged = isset($_SESSION['user_id']);
?>

<?php if ($isLogged): ?>
    <?php include 'includes/admin-header.php'; ?>
<?php endif; ?>

<div class="layout">

    <?php if ($isLogged): ?>
        <?php include 'includes/admin-sidebar.php'; ?>
    <?php endif; ?>

    <main class="main">

        <section style="text-align:center; padding:80px 20px;">

            <div style="
                max-width: 500px;
                margin: auto;
                padding: 40px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 3px 12px rgba(0,0,0,0.1);
            ">

                <i class="fas fa-lock" style="font-size: 64px; color: #e63946;"></i>

                <h1 style="margin-top:20px; font-size:32px; font-weight:700;">
                    Accès Refusé
                </h1>

                <p style="font-size:17px; color:#555; margin-top:10px;">
                    Vous n’avez pas la permission d’accéder à cette page.
                </p>

                <?php if ($isLogged): ?>
                    <p style="margin:10px 0; font-size:15px;">
                        Rôle connecté :
                        <strong><?= htmlspecialchars($_SESSION['user_role']) ?></strong>
                    </p>

                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin-dashboard.php" class="btn btn-primary" style="margin-top:20px;">
                            Retour au Tableau de Bord
                        </a>
                    <?php elseif ($_SESSION['user_role'] === 'agent'): ?>
                        <a href="../agent/agent-dashboard.php" class="btn btn-primary" style="margin-top:20px;">
                            Retour à l’Espace Agent
                        </a>
                    <?php else: ?>
                        <a href="../user/user-dashboard.php" class="btn btn-primary" style="margin-top:20px;">
                            Retour à votre Espace
                        </a>
                    <?php endif; ?>

                <?php else: ?>

                    <a href="../auth/login.php" class="btn btn-primary" style="margin-top:20px;">
                        Se connecter
                    </a>

                <?php endif; ?>

            </div>

        </section>

    </main>
</div>

<?php if ($isLogged): ?>
    <?php include 'includes/admin-footer.php'; ?>
<?php endif; ?>
