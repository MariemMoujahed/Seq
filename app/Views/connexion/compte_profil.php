<div class="page">
  <header class="page-header">
    <h1 class="page-title">Mon profil</h1>
    <p class="page-subtitle">Gérez vos informations personnelles et préférences</p>
  </header>

  <div class="profile-layout">
    <!-- Main Profile Card -->
    <section class="card profile-card" aria-labelledby="profile-heading">
      <div class="profile-header">
        <div class="profile-avatar" aria-hidden="true">
          <i class="fas fa-user"></i>
        </div>
        <div class="profile-identity">
          <h2 id="profile-heading" class="profile-name"><?= esc($profil['cpt_prenom'] . ' ' . $profil['cpt_nom']) ?></h2>
          <p class="profile-username">@<?= esc($profil['cpt_pseudo']) ?></p>
          <span class="badge badge-<?= $profil['cpt_role'] === 'A' ? 'valid' : 'pending' ?>">
            <i class="fas fa-<?= $profil['cpt_role'] === 'A' ? 'user-shield' : 'user' ?>"></i>
            <?= $profil['cpt_role'] === 'A' ? 'Administrateur' : 'Membre' ?>
          </span>
        </div>
      </div>

      <div class="divider"></div>

      <dl class="profile-details">
        <div class="profile-detail">
          <dt>Email</dt>
          <dd><a href="mailto:<?= esc($profil['cpt_email']) ?>"><?= esc($profil['cpt_email'] ?? '—') ?></a></dd>
        </div>
        <div class="profile-detail">
          <dt>Téléphone</dt>
          <dd><a href="tel:<?= esc($profil['cpt_telephone']) ?>"><?= esc($profil['cpt_telephone'] ?? '—') ?></a></dd>
        </div>
        <div class="profile-detail">
          <dt>Adresse</dt>
          <dd><?= esc($profil['cpt_adresse'] ?? '—') ?></dd>
        </div>
        <div class="profile-detail">
          <dt>Entreprise</dt>
          <dd><?= esc($profil['cpt_entreprise'] ?? '—') ?></dd>
        </div>
        <div class="profile-detail">
          <dt>Statut</dt>
          <dd>
            <span class="status-badge status-<?= ($profil['cpt_statut'] ?? 'A') === 'A' ? 'active' : 'inactive' ?>">
              <?= ($profil['cpt_statut'] ?? 'A') === 'A' ? 'Actif' : 'Inactif' ?>
            </span>
          </dd>
        </div>
        <div class="profile-detail">
          <dt>Rôle</dt>
          <dd><?= $profil['cpt_role'] === 'A' ? 'Administrateur' : 'Membre' ?></dd>
        </div>
      </dl>

      <div class="divider"></div>

      <div class="profile-actions">
        <a href="<?= base_url('index.php/compte/accueil') ?>" class="btn btn-ghost">
          <i class="fas fa-arrow-left"></i>
          Retour au tableau de bord
        </a>
        <a href="<?= base_url('index.php/compte/modifier_profil') ?>" class="btn btn-accent">
          <i class="fas fa-edit"></i>
          Modifier le profil
        </a>
      </div>
    </section>

    <!-- Sidebar Info Card -->
    <aside class="card profile-sidebar" aria-label="Informations du compte">
      <h3 class="section-title">Informations du compte</h3>
      <ul class="sidebar-info">
        <li>
          <i class="fas fa-shield-alt" aria-hidden="true"></i>
          <div>
            <strong>Sécurité</strong>
            <span>Connexion sécurisée active</span>
          </div>
        </li>
        <li>
          <i class="fas fa-clock" aria-hidden="true"></i>
          <div>
            <strong>Dernière connexion</strong>
            <span>À l'instant</span>
          </div>
        </li>
        <li>
          <i class="fas fa-key" aria-hidden="true"></i>
          <div>
            <strong>Mot de passe</strong>
            <span><a href="#" class="link-inline">Changer</a></span>
          </div>
        </li>
        <li>
          <i class="fas fa-bell" aria-hidden="true"></i>
          <div>
            <strong>Notifications</strong>
            <span><a href="#" class="link-inline">Préférences</a></span>
          </div>
        </li>
      </ul>

      <div class="divider" style="margin: 1rem 0;"></div>

      <h3 class="section-title">Accès rapides</h3>
      <ul class="sidebar-links">
        <li><a href="<?= base_url('index.php/devis/lister_dev') ?>"><i class="fas fa-file-invoice"></i> Mes devis</a></li>
        <li><a href="<?= base_url('index.php/produits/lister_prd') ?>"><i class="fas fa-box"></i> Catalogue produits</a></li>
        <?php if ($profil['cpt_role'] === 'A'): ?>
        <li><a href="<?= base_url('index.php/compte/lister') ?>"><i class="fas fa-users-cog"></i> Gestion utilisateurs</a></li>
        <?php endif; ?>
        <li><a href="<?= base_url('index.php/compte/deconnecter') ?>" class="text-danger"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
      </ul>
    </aside>
  </div>
</div>

<style>
/* Profile page styles matching theme.css */
.profile-layout {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: var(--gap);
  align-items: start;
}

.profile-card {
  padding: 2rem;
}

.profile-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}

.profile-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--c-accent-light);
  color: var(--c-accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  flex-shrink: 0;
}

.profile-identity {
  flex: 1;
  min-width: 0;
}

.profile-name {
  font-family: 'DM Serif Display', Georgia, serif;
  font-size: 1.5rem;
  font-weight: 400;
  color: var(--c-ink);
  margin: 0 0 .25rem;
}

.profile-username {
  color: var(--c-muted);
  font-size: .95rem;
  margin: 0 0 .75rem;
}

.profile-details {
  display: grid;
  gap: 1rem;
  margin: 1.5rem 0;
}

.profile-detail {
  display: grid;
  grid-template-columns: 140px 1fr;
  gap: 1rem;
  align-items: start;
  padding: .75rem 0;
  border-bottom: 1px solid var(--c-border);
}

.profile-detail:last-child {
  border-bottom: none;
}

.profile-detail dt {
  font-size: .8rem;
  font-weight: 600;
  color: var(--c-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
  margin: 0;
}

.profile-detail dd {
  margin: 0;
  color: var(--c-ink);
  font-size: .95rem;
  word-break: break-word;
}

.profile-detail a {
  color: var(--c-accent);
  text-decoration: none;
}

.profile-detail a:hover {
  text-decoration: underline;
}

.profile-actions {
  display: flex;
  gap: .75rem;
  flex-wrap: wrap;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--c-border);
}

.profile-sidebar {
  position: sticky;
  top: 1.5rem;
  padding: 1.5rem;
}

.sidebar-info {
  list-style: none;
  padding: 0;
  margin: 0 0 1.5rem;
}

.sidebar-info li {
  display: flex;
  align-items: flex-start;
  gap: .75rem;
  padding: .75rem 0;
  border-bottom: 1px solid var(--c-border);
}

.sidebar-info li:last-child {
  border-bottom: none;
}

.sidebar-info i {
  width: 20px;
  height: 20px;
  color: var(--c-accent);
  flex-shrink: 0;
  margin-top: .15rem;
}

.sidebar-info strong {
  display: block;
  font-size: .85rem;
  color: var(--c-ink);
  margin-bottom: .15rem;
}

.sidebar-info span {
  font-size: .8rem;
  color: var(--c-muted);
}

.sidebar-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar-links li {
  margin-bottom: .5rem;
}

.sidebar-links a {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: .6rem .75rem;
  color: var(--c-ink);
  border-radius: var(--radius-sm);
  font-size: .9rem;
  font-weight: 500;
  text-decoration: none;
  transition: background .15s, color .15s;
}

.sidebar-links a:hover {
  background: var(--c-accent-light);
  color: var(--c-accent);
}

.sidebar-links a.text-danger {
  color: var(--c-danger);
}

.sidebar-links a.text-danger:hover {
  background: var(--c-danger-bg);
  color: var(--c-danger);
}

.sidebar-links i {
  width: 1.2rem;
  text-align: center;
  color: var(--c-muted);
}

.sidebar-links a:hover i {
  color: var(--c-accent);
}

.sidebar-links a.text-danger:hover i {
  color: var(--c-danger);
}

.link-inline {
  color: var(--c-accent);
  font-weight: 500;
  text-decoration: none;
}

.link-inline:hover {
  text-decoration: underline;
}

/* Status badge reuse from theme */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 11px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 20px;
  white-space: nowrap;
}

.status-active {
  background: var(--c-accent-light);
  color: var(--c-accent);
}

.status-inactive {
  background: var(--c-danger-bg);
  color: var(--c-danger);
}

/* Badge from theme */
.badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 11px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 20px;
  white-space: nowrap;
}

.badge-valid {
  background: var(--c-accent-light);
  color: var(--c-accent);
}

.badge-pending {
  background: var(--c-pending-bg);
  color: var(--c-pending);
}

@media (max-width: 900px) {
  .profile-layout {
    grid-template-columns: 1fr;
  }
  
  .profile-sidebar {
    position: static;
  }
}

@media (max-width: 640px) {
  .profile-card {
    padding: 1.5rem;
  }
  
  .profile-header {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }
  
  .profile-detail {
    grid-template-columns: 1fr;
    gap: .25rem;
  }
  
  .profile-actions {
    flex-direction: column;
  }
  
  .profile-actions .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>