<div class="page">
  <header class="page-header">
    <div class="header-content">
      <div>
        <h1 class="page-title">Gestion des comptes</h1>
        <p class="page-subtitle">Administration des utilisateurs et profils</p>
      </div>
      <a href="<?= base_url('index.php/compte/creer') ?>" class="btn btn-accent">
        <i class="fas fa-user-plus"></i>
        Nouvel utilisateur
      </a>
    </div>
  </header>

  <!-- Stats Cards -->
  <section class="stats-grid" aria-label="Statistiques">
    <article class="stat-card" style="--accent: var(--c-accent); --accent-bg: var(--c-accent-light);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-users"></i></div>
      <div class="stat-content">
        <p class="stat-label">Total comptes</p>
        <p class="stat-value"><?= esc($membre->total ?? 0) ?></p>
        <p class="stat-trend"><i class="fas fa-user-plus"></i> +3 ce mois</p>
      </div>
    </article>
    <article class="stat-card" style="--accent: #36b9cc; --accent-bg: rgba(54,185,204,.15);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-id-card"></i></div>
      <div class="stat-content">
        <p class="stat-label">Profils actifs</p>
        <p class="stat-value"><?= esc($profil_num->total_profil ?? 0) ?></p>
      </div>
    </article>
    <article class="stat-card" style="--accent: #f6c23e; --accent-bg: rgba(246,194,62,.15);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-user-shield"></i></div>
      <div class="stat-content">
        <p class="stat-label">Administrateurs</p>
        <p class="stat-value">
          <?php 
            $adminCount = 0;
            if (!empty($logins) && is_array($logins)) {
              foreach ($logins as $u) if (($u['cpt_role'] ?? '') === 'A') $adminCount++;
            }
            echo $adminCount;
          ?>
        </p>
      </div>
    </article>
    <article class="stat-card" style="--accent: #e74a3b; --accent-bg: rgba(231,74,59,.15);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-user-slash"></i></div>
      <div class="stat-content">
        <p class="stat-label">Désactivés</p>
        <p class="stat-value">
          <?php 
            $inactiveCount = 0;
            if (!empty($logins) && is_array($logins)) {
              foreach ($logins as $u) if (($u['cpt_statut'] ?? '') !== 'A') $inactiveCount++;
            }
            echo $inactiveCount;
          ?>
        </p>
      </div>
    </article>
  </section>

  <!-- Filters & Search -->
  <section class="card filters-card" aria-label="Filtres et recherche">
    <form class="filters-form" method="get" action="<?= base_url('index.php/compte/lister') ?>">
      <div class="filters-row">
        <div class="search-group">
          <label for="search" class="visually-hidden">Rechercher</label>
          <div class="search-input-wrap">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="search" id="search" name="search" placeholder="Rechercher pseudo, nom, email..." 
                   value="<?= esc($_GET['search'] ?? '') ?>" autocomplete="off">
            <?php if (!empty($_GET['search'])): ?>
              <a href="<?= base_url('index.php/compte/lister') ?>" class="search-clear" aria-label="Effacer la recherche">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <div class="filter-group">
          <label for="filter-role" class="visually-hidden">Filtrer par rôle</label>
          <select id="filter-role" name="role">
            <option value="">Tous les rôles</option>
            <option value="A" <?= ($_GET['role'] ?? '') === 'A' ? 'selected' : '' ?>>Administrateurs</option>
            <option value="M" <?= ($_GET['role'] ?? '') === 'M' ? 'selected' : '' ?>>Membres</option>
            <option value="I" <?= ($_GET['role'] ?? '') === 'I' ? 'selected' : '' ?>>Invités</option>
          </select>
        </div>

        <div class="filter-group">
          <label for="filter-status" class="visually-hidden">Filtrer par statut</label>
          <select id="filter-status" name="status">
            <option value="">Tous les statuts</option>
            <option value="A" <?= ($_GET['status'] ?? '') === 'A' ? 'selected' : '' ?>>Activés</option>
            <option value="D" <?= ($_GET['status'] ?? '') === 'D' ? 'selected' : '' ?>>Désactivés</option>
          </select>
        </div>

        <div class="filter-group">
          <label for="sort" class="visually-hidden">Trier par</label>
          <select id="sort" name="sort">
            <option value="pseudo_asc" <?= ($_GET['sort'] ?? '') === 'pseudo_asc' ? 'selected' : '' ?>>Pseudo A-Z</option>
            <option value="pseudo_desc" <?= ($_GET['sort'] ?? '') === 'pseudo_desc' ? 'selected' : '' ?>>Pseudo Z-A</option>
            <option value="nom_asc" <?= ($_GET['sort'] ?? '') === 'nom_asc' ? 'selected' : '' ?>>Nom A-Z</option>
            <option value="date_desc" <?= ($_GET['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Plus récents</option>
            <option value="date_asc" <?= ($_GET['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Plus anciens</option>
          </select>
        </div>
      </div>
    </form>
  </section>

  <!-- Users Table -->
  <section class="card table-card" aria-labelledby="table-heading">
    <div class="table-header">
      <h2 id="table-heading" class="section-title"><?= esc($titre ?? 'Liste de tous les profils') ?></h2>
      <div class="table-meta">
        <span class="results-count">
          <?php 
            $total = !empty($logins) && is_array($logins) ? count($logins) : 0;
            echo $total . ' utilisateur' . ($total > 1 ? 's' : '');
          ?>
        </span>
      </div>
    </div>

    <?php if (!empty($logins) && is_array($logins)): ?>
      <div class="table-wrap">
        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th scope="col" data-sort="pseudo">
                  <a href="<?= (function($field) { $params = $_GET; $currentSort = $params['sort'] ?? ''; $currentDir = 'asc'; if (str_ends_with($currentSort, '_desc')) { $currentDir = 'desc'; } $baseField = str_replace(['_asc', '_desc'], '', $currentSort); if ($baseField === $field) { $params['sort'] = $field . '_' . ($currentDir === 'asc' ? 'desc' : 'asc'); } else { $params['sort'] = $field . '_asc'; } return base_url('index.php/compte/lister') . '?' . http_build_query($params); })('pseudo') ?>" class="sortable">
                    Utilisateur <i class="fas fa-sort"></i>
                  </a>
                </th>
                <th scope="col" data-sort="nom">
                  <a href="<?= (function($field) { $params = $_GET; $currentSort = $params['sort'] ?? ''; $currentDir = 'asc'; if (str_ends_with($currentSort, '_desc')) { $currentDir = 'desc'; } $baseField = str_replace(['_asc', '_desc'], '', $currentSort); if ($baseField === $field) { $params['sort'] = $field . '_' . ($currentDir === 'asc' ? 'desc' : 'asc'); } else { $params['sort'] = $field . '_asc'; } return base_url('index.php/compte/lister') . '?' . http_build_query($params); })('nom') ?>" class="sortable">
                    Nom <i class="fas fa-sort"></i>
                  </a>
                </th>
                <th scope="col">Prénom</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Email</th>
                <th scope="col" data-sort="statut">
                  <a href="<?= (function($field) { $params = $_GET; $currentSort = $params['sort'] ?? ''; $currentDir = 'asc'; if (str_ends_with($currentSort, '_desc')) { $currentDir = 'desc'; } $baseField = str_replace(['_asc', '_desc'], '', $currentSort); if ($baseField === $field) { $params['sort'] = $field . '_' . ($currentDir === 'asc' ? 'desc' : 'asc'); } else { $params['sort'] = $field . '_asc'; } return base_url('index.php/compte/lister') . '?' . http_build_query($params); })('statut') ?>" class="sortable">
                    Statut <i class="fas fa-sort"></i>
                  </a>
                </th>
                <th scope="col" data-sort="role">
                  <a href="<?= (function($field) { $params = $_GET; $currentSort = $params['sort'] ?? ''; $currentDir = 'asc'; if (str_ends_with($currentSort, '_desc')) { $currentDir = 'desc'; } $baseField = str_replace(['_asc', '_desc'], '', $currentSort); if ($baseField === $field) { $params['sort'] = $field . '_' . ($currentDir === 'asc' ? 'desc' : 'asc'); } else { $params['sort'] = $field . '_asc'; } return base_url('index.php/compte/lister') . '?' . http_build_query($params); })('role') ?>" class="sortable">
                    Rôle <i class="fas fa-sort"></i>
                  </a>
                </th>
                <th scope="col" style="width: 140px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logins as $index => $user): ?>
                <tr data-pseudo="<?= esc($user['cpt_pseudo']) ?>" style="animation-delay: <?= min($index * 0.03, 0.3) ?>s;">
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar" aria-hidden="true">
                        <i class="fas fa-<?= $user['cpt_role'] === 'A' ? 'user-shield' : 'user' ?>"></i>
                      </div>
                      <div class="user-info">
                        <strong class="user-pseudo"><?= esc($user['cpt_pseudo']) ?></strong>
                        <span class="user-id">ID: <?= esc($user['cpt_pseudo']) ?></span>
                      </div>
                    </div>
                  </td>
                  <td><?= esc($user['cpt_nom']) ?></td>
                  <td><?= esc($user['cpt_prenom']) ?></td>
                  <td>
                    <a href="tel:<?= esc($user['cpt_telephone']) ?>" class="contact-link">
                      <i class="fas fa-phone" aria-hidden="true"></i>
                      <?= esc($user['cpt_telephone']) ?>
                    </a>
                  </td>
                  <td>
                    <a href="mailto:<?= esc($user['cpt_email']) ?>" class="contact-link">
                      <i class="fas fa-envelope" aria-hidden="true"></i>
                      <?= esc($user['cpt_email']) ?>
                    </a>
                  </td>
                  <td>
                    <span class="badge badge-<?= $user['cpt_statut'] === 'A' ? 'valid' : 'pending' ?> status-badge">
                      <i class="fas fa-<?= $user['cpt_statut'] === 'A' ? 'check-circle' : 'times-circle' ?>" aria-hidden="true"></i>
                      <?= $user['cpt_statut'] === 'A' ? 'Activé' : 'Désactivé' ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-<?= $user['cpt_role'] === 'A' ? 'valid' : 'pending' ?> role-badge">
                      <i class="fas fa-<?= $user['cpt_role'] === 'A' ? 'user-shield' : ($user['cpt_role'] === 'M' ? 'user' : 'user-tag') ?>" aria-hidden="true"></i>
                      <?= $user['cpt_role'] === 'A' ? 'Administrateur' : ($user['cpt_role'] === 'M' ? 'Membre' : 'Invité') ?>
                    </span>
                  </td>
                  <td>
                    <div class="actions-cell">
                      <a href="<?= base_url('index.php/compte/toggle/' . $user['cpt_pseudo']) ?>"
                         class="btn btn-icon btn-<?= $user['cpt_statut'] === 'A' ? 'ghost' : 'accent' ?>"
                         title="<?= $user['cpt_statut'] === 'A' ? 'Désactiver' : 'Activer' ?>"
                         data-tooltip="<?= $user['cpt_statut'] === 'A' ? 'Désactiver' : 'Activer' ?>">
                        <i class="fas fa-<?= $user['cpt_statut'] === 'A' ? 'user-slash' : 'user-check' ?>"></i>
                      </a>
                      <a href="<?= base_url('index.php/compte/afficher_profil') ?>?user=<?= urlencode($user['cpt_pseudo']) ?>"
                         class="btn btn-icon btn-ghost"
                         title="Voir le profil"
                         data-tooltip="Voir le profil">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="<?= base_url('index.php/compte/delete/' . $user['cpt_pseudo']) ?>"
                         class="btn btn-icon btn-danger"
                         title="Supprimer"
                         data-tooltip="Supprimer"
                         onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ? Cette action est irréversible.');">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination placeholder -->
      <?php if ($total > 10): ?>
        <nav class="pagination" aria-label="Pagination">
          <a href="#" class="btn btn-ghost btn-sm" aria-label="Page précédente"><i class="fas fa-chevron-left"></i></a>
          <span class="page-numbers">
            <a href="#" class="active" aria-current="page">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <span class="ellipsis">…</a>
            <a href="#">5</a>
          </span>
          <a href="#" class="btn btn-ghost btn-sm" aria-label="Page suivante"><i class="fas fa-chevron-right"></i></a>
        </nav>
      <?php endif; ?>

    <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon" aria-hidden="true">
          <i class="fas fa-users"></i>
        </div>
        <h3>Aucun utilisateur trouvé</h3>
        <p><?= !empty($_GET['search']) || !empty($_GET['role']) || !empty($_GET['status']) 
          ? 'Aucun résultat ne correspond à vos critères de recherche.' 
          : 'Aucun compte pour le moment. Commencez par créer le premier utilisateur.' ?></p>
        <a href="<?= base_url('index.php/compte/creer') ?>" class="btn btn-accent" style="margin-top: 1rem;">
          <i class="fas fa-user-plus"></i>
          Créer un utilisateur
        </a>
      </div>
    <?php endif; ?>
  </section>
</div>

<style>
/* Enhanced user management page styles */

/* Page header with action */
.header-content {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1.5rem;
  flex-wrap: wrap;
  margin-bottom: var(--gap);
}

.header-content .page-title {
  margin-bottom: .25rem;
}

/* Stats grid enhancements */
.stats-grid {
  margin-bottom: var(--gap);
}

.stat-trend {
  display: inline-flex;
  align-items: center;
  gap: .3rem;
  font-size: .75rem;
  font-weight: 500;
  color: var(--c-accent);
  margin-top: .35rem;
}

.stat-trend i {
  font-size: .7rem;
}

/* Filters card */
.filters-card {
  padding: 1.25rem;
  margin-bottom: var(--gap);
  background: var(--c-surface);
  border-color: var(--c-border);
}

.filters-form {
  margin: 0;
}

.filters-row {
  display: grid;
  grid-template-columns: 1fr auto auto auto;
  gap: 1rem;
  align-items: end;
}

.search-group {
  min-width: 280px;
}

.search-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.search-input-wrap i {
  position: absolute;
  left: 1rem;
  color: var(--c-muted);
  font-size: .9rem;
}

.search-input-wrap input {
  width: 100%;
  padding: .6rem 1rem .6rem 2.75rem;
  font-family: inherit;
  font-size: 14px;
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius-sm);
  color: var(--c-ink);
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}

.search-input-wrap input:focus {
  border-color: var(--c-accent-mid);
  box-shadow: 0 0 0 3px var(--c-accent-light);
}

.search-clear {
  position: absolute;
  right: .75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  color: var(--c-muted);
  background: var(--c-surface);
  text-decoration: none;
  transition: all .15s;
}

.search-clear:hover {
  color: var(--c-danger);
  background: var(--c-danger-bg);
}

.filter-group select {
  min-width: 160px;
  padding: .6rem 2.5rem .6rem .75rem;
  font-family: inherit;
  font-size: 14px;
  background: var(--c-card) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23888780' d='M1 1l5 5 5-5'/%3E%3C/svg%3E") no-repeat right .75rem center;
  border: 1px solid var(--c-border);
  border-radius: var(--radius-sm);
  color: var(--c-ink);
  outline: none;
  appearance: none;
  cursor: pointer;
  transition: border-color .15s, box-shadow .15s;
}

.filter-group select:focus {
  border-color: var(--c-accent-mid);
  box-shadow: 0 0 0 3px var(--c-accent-light);
}

.filter-group select:hover {
  border-color: var(--c-accent-mid);
}

/* Table card */
.table-card {
  overflow: hidden;
}

.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--c-border);
  flex-wrap: wrap;
  gap: .75rem;
}

.results-count {
  font-size: .85rem;
  color: var(--c-muted);
  font-weight: 500;
}

/* Table enhancements */
.table-wrap table {
  min-width: 900px;
}

.table-wrap th {
  cursor: pointer;
  user-select: none;
  white-space: nowrap;
}

.table-wrap th.sortable a {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  color: var(--c-muted);
  text-decoration: none;
  font-weight: 600;
  transition: color .15s;
}

.table-wrap th.sortable a:hover,
.table-wrap th.sortable a[aria-sort] {
  color: var(--c-accent);
}

.table-wrap th i.fa-sort {
  opacity: .4;
  font-size: .7rem;
}

.table-wrap th[aria-sort="asc"] i.fa-sort-up,
.table-wrap th[aria-sort="desc"] i.fa-sort-down {
  opacity: 1;
  color: var(--c-accent);
}

.table-wrap th[aria-sort="asc"] i.fa-sort-down,
.table-wrap th[aria-sort="desc"] i.fa-sort-up {
  display: none;
}

/* User cell */
.user-cell {
  display: flex;
  align-items: center;
  gap: .75rem;
  white-space: nowrap;
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--c-accent-light);
  color: var(--c-accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .9rem;
  flex-shrink: 0;
}

.user-pseudo {
  display: block;
  font-size: .9rem;
  color: var(--c-ink);
}

.user-id {
  display: block;
  font-size: .7rem;
  color: var(--c-muted);
  font-family: monospace;
}

/* Contact links */
.contact-link {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  color: var(--c-ink);
  text-decoration: none;
  font-size: .875rem;
  transition: color .15s;
}

.contact-link:hover {
  color: var(--c-accent);
  text-decoration: underline;
}

.contact-link i {
  color: var(--c-muted);
  font-size: .75rem;
}

.contact-link:hover i {
  color: var(--c-accent);
}

/* Badges with icons */
.status-badge,
.role-badge {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
}

.status-badge i,
.role-badge i {
  font-size: .65rem;
}

/* Actions cell */
.actions-cell {
  display: flex;
  align-items: center;
  gap: .35rem;
  justify-content: flex-start;
}

.actions-cell .btn-icon {
  width: 36px;
  height: 36px;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-sm);
  transition: all .15s;
}

.actions-cell .btn-icon:hover {
  transform: scale(1.1);
}

/* Tooltip */
[data-tooltip] {
  position: relative;
}

[data-tooltip]::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: 120%;
  left: 50%;
  transform: translateX(-50%);
  padding: .4rem .6rem;
  font-size: .7rem;
  font-weight: 500;
  color: var(--c-card);
  background: var(--c-ink);
  border-radius: 4px;
  white-space: nowrap;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity .15s, transform .15s;
  z-index: 100;
}

[data-tooltip]::before {
  content: "";
  position: absolute;
  bottom: 110%;
  left: 50%;
  transform: translateX(-50%);
  border: 5px solid transparent;
  border-top-color: var(--c-ink);
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity .15s;
  z-index: 100;
}

[data-tooltip]:hover::after,
[data-tooltip]:hover::before {
  opacity: 1;
  visibility: visible;
}

[data-tooltip]:hover::after {
  transform: translateX(-50%) translateY(-4px);
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 1.5rem;
  border-radius: 50%;
  background: var(--c-accent-light);
  color: var(--c-accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
}

.empty-state h3 {
  font-family: 'DM Serif Display', Georgia, serif;
  font-size: 1.25rem;
  font-weight: 400;
  color: var(--c-ink);
  margin: 0 0 .5rem;
}

.empty-state p {
  color: var(--c-muted);
  margin: 0 0 1.5rem;
  font-size: .95rem;
}

/* Pagination */
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  padding: 1.5rem 0 0;
  margin-top: 1rem;
  border-top: 1px solid var(--c-border);
  flex-wrap: wrap;
}

.page-numbers {
  display: flex;
  align-items: center;
  gap: .25rem;
}

.page-numbers a,
.page-numbers span {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 .5rem;
  border-radius: var(--radius-sm);
  font-size: .85rem;
  font-weight: 500;
  color: var(--c-ink);
  text-decoration: none;
  transition: all .15s;
}

.page-numbers a:hover {
  background: var(--c-accent-light);
  color: var(--c-accent);
}

.page-numbers a.active {
  background: var(--c-accent);
  color: #fff;
}

.page-numbers .ellipsis {
  color: var(--c-muted);
  pointer-events: none;
}

/* Responsive */
@media (max-width: 900px) {
  .filters-row {
    grid-template-columns: 1fr;
    align-items: stretch;
  }
  
  .search-group {
    min-width: 0;
  }
  
  .filter-group select {
    width: 100%;
    min-width: 0;
  }
  
  .header-content {
    flex-direction: column;
    align-items: stretch;
  }
  
  .header-content .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .table-wrap th:nth-child(4),
  .table-wrap th:nth-child(5),
  .table-wrap td:nth-child(4),
  .table-wrap td:nth-child(5) {
    display: none;
  }
  
  .user-id {
    display: none;
  }
  
  .pagination {
    gap: .25rem;
  }
  
  .page-numbers a,
  .page-numbers span {
    min-width: 32px;
    height: 32px;
    font-size: .8rem;
  }
}
</style>