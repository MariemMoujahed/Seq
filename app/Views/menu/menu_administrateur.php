<!-- Navbar Wrapper (Horizontal) -->
<nav class="navbar navbar-expand bg-dark text-white">
    <a class="navbar-brand d-flex align-items-center text-white">
        <div class="sidebar-brand-icon rotate-n-15 me-2">
            <i class="fas fa-laugh-wink" aria-hidden="true"></i>
        </div>
        <span>Admin Panel</span>
    </a>

    <ul class="navbar-nav ms-auto d-flex flex-row">
        <li class="nav-item mx-2">
            <a class="nav-link text-white" href="<?= base_url('index.php/compte/accueil') ?>">
                <i class="fas fa-fw fa-calendar-alt me-1" aria-hidden="true"></i>
                Acceuil
            </a>
        </li>
        <li class="nav-item mx-2">
            <a class="nav-link text-white" href="<?= base_url('index.php/devis/lister_dev') ?>">
                <i class="fas fa-fw fa-calendar-alt me-1" aria-hidden="true"></i>
                Voir les devis
            </a>
        </li>
        <li class="nav-item mx-2">
            <a class="nav-link text-white" href="<?= base_url('index.php/compte/afficher_profil') ?>">
                <i class="fas fa-fw fa-user me-1" aria-hidden="true"></i>
                Mon profil
            </a>
        </li>
        <li class="nav-item mx-2">
            <a class="nav-link text-white" href="<?= base_url('index.php/compte/lister') ?>">
                <i class="fas fa-fw fa-user me-1" aria-hidden="true"></i>
                Gestion des Comptes/Profils
            </a>
        </li>
        <li class="nav-item mx-2">
            <a class="nav-link text-white" href="<?= base_url('index.php/produits/lister_prd') ?>">
                <i class="fas fa-fw fa-box me-1" aria-hidden="true"></i>
                Gestion des produits
            </a>
        </li>

        <li class="nav-item mx-2">
            <a class="nav-link text-white" href="<?= base_url('index.php/compte/deconnecter') ?>">
                <i class="fas fa-fw fa-sign-out-alt me-1" aria-hidden="true"></i>
                Déconnexion
            </a>
        </li>
    </ul>
</nav>