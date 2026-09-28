<header class="site-header">
  <div class="header-container">
    <div class="header-brand">
      <a href="<?= base_url('index.php') ?>" class="logo-link" aria-label="SIAM Interactive - Accueil">
        <svg class="logo-icon" viewBox="0 0 40 40" fill="none" aria-hidden="true">
          <rect x="2" y="2" width="36" height="36" rx="8" stroke="currentColor" stroke-width="2"/>
          <path d="M12 20h16M20 12v16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
        <span class="logo-text">SIAM <span class="logo-accent">Interactive</span></span>
      </a>
    </div>

    <nav class="header-nav" aria-label="Navigation principale">
      <a href="#services" class="nav-link">Solutions</a>
      <a href="#realisations" class="nav-link">Réalisations</a>
      <a href="#accompagnement" class="nav-link">Accompagnement</a>
      <a href="#contact" class="nav-link">Contact</a>
    </nav>

    <div class="header-actions">
      <a href="<?= base_url('index.php/compte/creer') ?>" class="btn-header btn-header-ghost">
        <span>Créer un compte</span>
      </a>
      <a href="<?= base_url('index.php/compte/connecter') ?>" class="btn-header btn-header-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <span>Se connecter</span>
      </a>
    </div>
  </div>
</header>