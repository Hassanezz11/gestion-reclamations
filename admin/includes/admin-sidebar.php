<?php
// admin/includes/admin-sidebar.php
// $active_menu can be: 'dashboard', 'agents', 'users', 'reclamations', 'categories', 'profile'

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
        <a href="admin-dashboard.php"
           class="nav-item <?= $active_menu === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Tableau de Bord</span>
        </a>

        <a href="manage-agents.php"
           class="nav-item <?= $active_menu === 'agents' ? 'active' : '' ?>">
            <i class="fas fa-user-tie"></i>
            <span>Gérer Agents</span>
        </a>

        <a href="manage-users.php"
           class="nav-item <?= $active_menu === 'users' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Gérer Utilisateurs</span>
        </a>

        <a href="manage-reclamations.php"
           class="nav-item <?= $active_menu === 'reclamations' ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i>
            <span>Réclamations</span>
        </a>

        <a href="manage-categories.php"
           class="nav-item <?= $active_menu === 'categories' ? 'active' : '' ?>">
            <i class="fas fa-folder"></i>
            <span>Catégories</span>
        </a>

        <a href="admin-profile.php"
           class="nav-item <?= $active_menu === 'profile' ? 'active' : '' ?>">
            <i class="fas fa-user-shield"></i>
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
