<?php
// user/includes/user-sidebar.php
// $active_menu can be: dashboard, add, list, profile
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
        <a href="user-dashboard.php"
           class="nav-item <?= $active_menu === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Tableau de bord</span>
        </a>

        <a href="add-reclamation.php"
           class="nav-item <?= $active_menu === 'add' ? 'active' : '' ?>">
            <i class="fas fa-plus"></i>
            <span>Nouvelle réclamation</span>
        </a>

        <a href="my-reclamations.php"
           class="nav-item <?= $active_menu === 'list' ? 'active' : '' ?>">
            <i class="fas fa-list"></i>
            <span>Mes réclamations</span>
        </a>

        <a href="profile.php"
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
