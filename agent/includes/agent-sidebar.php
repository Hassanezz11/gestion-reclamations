<?php
// agent/includes/agent-sidebar.php
// $active_menu can be: dashboard, reclamations, profile
if (!isset($active_menu)) {
    $active_menu = '';
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-file-alt"></i>
        <span>ReclamPro</span>
    </div>

    <nav class="sidebar-nav">
        <a href="agent-dashboard.php"
           class="nav-item <?= $active_menu === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Tableau de bord</span>
        </a>

        <a href="manage-reclamations.php"
           class="nav-item <?= $active_menu === 'reclamations' ? 'active' : '' ?>">
            <i class="fas fa-tasks"></i>
            <span>Gérer les réclamations</span>
        </a>

        <a href="agent-profile.php"
           class="nav-item <?= $active_menu === 'profile' ? 'active' : '' ?>">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="../auth/logout.php" class="nav-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>
