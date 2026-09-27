<nav class="site-nav">
    <a class="site-nav-brand" href="<?= base_url('index.php/compte/accueil') ?>">
        <i class="fas fa-laugh-wink" aria-hidden="true"></i>
        <span>Espace Membre</span>
    </a>

    <ul class="site-nav-links">
        <li>
            <a class="site-nav-link" href="<?= base_url('index.php/compte/accueil') ?>">
                <i class="fas fa-home" aria-hidden="true"></i>
                Accueil
            </a>
        </li>
        <li>
            <a class="site-nav-link" href="<?= base_url('index.php/devis/lister_dev') ?>">
                <i class="fas fa-file-invoice" aria-hidden="true"></i>
                Lister les devis
            </a>
        </li>
        <li>
            <a class="site-nav-link" href="<?= base_url('index.php/compte/afficher_profil') ?>">
                <i class="fas fa-user" aria-hidden="true"></i>
                Mon profil
            </a>
        </li>
        <li>
            <a class="site-nav-link is-logout" href="<?= base_url('index.php/compte/deconnecter') ?>">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                Déconnexion
            </a>
        </li>
    </ul>
</nav>
