<div class="page">
  <header class="page-header">
    <h1 class="page-title">Tableau de bord</h1>
    <p class="page-subtitle">Bienvenue <?= esc(session()->get('user')) ?>, voici un aperçu de votre activité</p>
  </header>

  <!-- Stats Cards -->
  <section class="stats-grid" aria-label="Statistiques principales">
    <article class="stat-card" style="--accent: var(--c-accent); --accent-bg: var(--c-accent-light);">
      <div class="stat-icon" aria-hidden="true">
        <i class="fas fa-users"></i>
      </div>
      <div class="stat-content">
        <p class="stat-label">Total utilisateurs</p>
        <p class="stat-value"><?= esc($membre ?? 0) ?></p>
        <p class="stat-trend"><i class="fas fa-arrow-up"></i> +12% ce mois</p>
      </div>
    </article>

    <article class="stat-card" style="--accent: #36b9cc; --accent-bg: rgba(54,185,204,.15);">
      <div class="stat-icon" aria-hidden="true">
        <i class="fas fa-file-invoice"></i>
      </div>
      <div class="stat-content">
        <p class="stat-label">Devis en attente</p>
        <p class="stat-value"><?= esc($devis_pending ?? 0) ?></p>
        <p class="stat-trend"><i class="fas fa-clock"></i> À traiter</p>
      </div>
    </article>

    <article class="stat-card" style="--accent: #f6c23e; --accent-bg: rgba(246,194,62,.15);">
      <div class="stat-icon" aria-hidden="true">
        <i class="fas fa-box"></i>
      </div>
      <div class="stat-content">
        <p class="stat-label">Produits actifs</p>
        <p class="stat-value"><?= esc($produits_count ?? 0) ?></p>
        <p class="stat-trend"><i class="fas fa-check-circle"></i> En stock</p>
      </div>
    </article>

    <article class="stat-card" style="--accent: #e74a3b; --accent-bg: rgba(231,74,59,.15);">
      <div class="stat-icon" aria-hidden="true">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="stat-content">
        <p class="stat-label">Alertes stock</p>
        <p class="stat-value"><?= esc($stock_alerts ?? 0) ?></p>
        <p class="stat-trend"><i class="fas fa-arrow-down"></i> Sous seuil</p>
      </div>
    </article>
  </section>

  <div class="dashboard-grid">
    <!-- Quick Actions -->
    <section class="card" aria-label="Actions rapides">
      <h2 class="section-title">Actions rapides</h2>
      <div class="quick-actions">
        <a href="<?= base_url('index.php/devis/lister_dev') ?>" class="quick-action" aria-label="Voir tous les devis">
          <div class="quick-action-icon" style="background: var(--c-accent-light); color: var(--c-accent);">
            <i class="fas fa-file-invoice"></i>
          </div>
          <div class="quick-action-info">
            <h3>Gérer les devis</h3>
            <p>Voir, valider ou modifier les devis</p>
          </div>
        </a>
        <a href="<?= base_url('index.php/produits/lister_prd') ?>" class="quick-action" aria-label="Gérer les produits">
          <div class="quick-action-icon" style="background: rgba(54,185,204,.15); color: #36b9cc;">
            <i class="fas fa-box"></i>
          </div>
          <div class="quick-action-info">
            <h3>Catalogue produits</h3>
            <p>Ajouter, modifier, supprimer</p>
          </div>
        </a>
        <a href="<?= base_url('index.php/compte/lister') ?>" class="quick-action" aria-label="Gérer les comptes">
          <div class="quick-action-icon" style="background: rgba(246,194,62,.15); color: #f6c23e;">
            <i class="fas fa-users-cog"></i>
          </div>
          <div class="quick-action-info">
            <h3>Comptes & profils</h3>
            <p>Administration des utilisateurs</p>
          </div>
        </a>
        <a href="<?= base_url('index.php/compte/afficher_profil') ?>" class="quick-action" aria-label="Mon profil">
          <div class="quick-action-icon" style="background: rgba(231,74,59,.15); color: #e74a3b;">
            <i class="fas fa-user"></i>
          </div>
          <div class="quick-action-info">
            <h3>Mon profil</h3>
            <p>Modifier mes informations</p>
          </div>
        </a>
      </div>
    </section>

    <!-- Recent Activity / Quick Stats -->
    <section class="card" aria-label="Activité récente">
      <div class="section-header">
        <h2 class="section-title">Activité récente</h2>
        <a href="<?= base_url('index.php/devis/lister_dev') ?>" class="btn btn-ghost btn-sm">Voir tout</a>
      </div>
      <?php if (isset($recent_devis) && !empty($recent_devis)): ?>
        <div class="table-wrap">
          <div class="table-scroll">
            <table>
              <thead>
                <tr>
                  <th>Référence</th>
                  <th>Client</th>
                  <th>Date</th>
                  <th>Montant</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recent_devis as $devis): ?>
                <tr>
                  <td><strong><?= esc($devis['dev_reference']) ?></strong></td>
                  <td>
                    <?= esc($devis['dev_client_nom'] ?? '—') ?>
                    <?php if (!empty($devis['dev_client_email'])): ?>
                      <span class="cell-sub"><?= esc($devis['dev_client_email']) ?></span>
                    <?php endif; ?>
                  </td>
                  <td><?= date('d/m/Y', strtotime($devis['dev_date'])) ?></td>
                  <td class="amount"><?= number_format($devis['dev_total_ttc'] ?? 0, 2, ',', ' ') ?> €</td>
                  <td>
                    <span class="badge badge-<?= $devis['dev_statut'] === 'validé' ? 'valid' : 'pending' ?>">
                      <?= esc(ucfirst($devis['dev_statut'] ?? 'en attente')) ?>
                    </span>
                  </td>
                  <td>
                    <div class="row-actions">
                      <a href="<?= base_url('index.php/devis/valider/' . $devis['dev_id']) ?>" class="btn btn-icon btn-accent" title="Valider" aria-label="Valider le devis">
                        <i class="fas fa-check"></i>
                      </a>
                      <a href="<?= base_url('index.php/devis/modifier_main_oeuvre/' . $devis['dev_id']) ?>" class="btn btn-icon btn-ghost" title="Modifier" aria-label="Modifier le devis">
                        <i class="fas fa-edit"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-inbox" style="font-size: 3rem; color: var(--c-muted); margin-bottom: 1rem;"></i>
          <p>Aucun devis récent. <a href="<?= base_url('index.php/devis/lister_dev') ?>" style="color: var(--c-accent);">Créer le premier</a></p>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>

<style>
/* Dashboard specific styles */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: var(--gap);
  margin-bottom: var(--gap);
}

.stat-card {
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius);
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.25rem;
  box-shadow: 0 1px 3px rgba(0,0,0,.05);
  transition: transform .2s, box-shadow .2s;
  border-left: 4px solid var(--accent);
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0,0,0,.08);
}

.stat-icon {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  background: var(--accent-bg);
  color: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.stat-content {
  flex: 1;
  min-width: 0;
}

.stat-label {
  font-size: .8rem;
  font-weight: 600;
  color: var(--c-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
  margin: 0 0 .25rem;
}

.stat-value {
  font-family: 'DM Serif Display', Georgia, serif;
  font-size: 2rem;
  font-weight: 400;
  color: var(--c-ink);
  margin: 0 0 .35rem;
  line-height: 1.2;
}

.stat-trend {
  display: inline-flex;
  align-items: center;
  gap: .3rem;
  font-size: .75rem;
  font-weight: 500;
  color: var(--c-accent);
  margin: 0;
}

.stat-trend i {
  font-size: .7rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--gap);
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: .75rem;
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: .75rem;
}

.quick-action {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.25rem;
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: var(--radius-sm);
  text-decoration: none;
  transition: all .15s;
}

.quick-action:hover {
  border-color: var(--c-accent-mid);
  background: var(--c-accent-light);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59,109,17,.15);
}

.quick-action-icon {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  flex-shrink: 0;
}

.quick-action-info h3 {
  font-size: .95rem;
  font-weight: 600;
  color: var(--c-ink);
  margin: 0 0 .15rem;
}

.quick-action-info p {
  font-size: .8rem;
  color: var(--c-muted);
  margin: 0;
}

@media (max-width: 900px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .quick-actions {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .stat-card {
    padding: 1.15rem;
  }
  
  .stat-value {
    font-size: 1.6rem;
  }
}
</style>