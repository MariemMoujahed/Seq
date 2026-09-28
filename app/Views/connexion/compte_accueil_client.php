<div class="page">
  <header class="page-header">
    <h1 class="page-title">Mon espace client</h1>
    <p class="page-subtitle">Bienvenue <?= esc($profil['cpt_prenom'] ?? 'Client') ?>, voici votre tableau de bord personnel</p>
  </header>

  <!-- Stats Cards -->
  <section class="stats-grid" aria-label="Mes statistiques">
    <article class="stat-card" style="--accent: var(--c-accent); --accent-bg: var(--c-accent-light);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-file-invoice"></i></div>
      <div class="stat-content">
        <p class="stat-label">Mes devis</p>
        <p class="stat-value"><?= esc($devis_total) ?></p>
        <p class="stat-trend"><i class="fas fa-folder"></i> Total</p>
      </div>
    </article>
    <article class="stat-card" style="--accent: #f6c23e; --accent-bg: rgba(246,194,62,.15);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-clock"></i></div>
      <div class="stat-content">
        <p class="stat-label">En attente</p>
        <p class="stat-value"><?= esc($devis_pending) ?></p>
        <p class="stat-trend"><i class="fas fa-hourglass-half"></i> À valider</p>
      </div>
    </article>
    <article class="stat-card" style="--accent: var(--c-accent); --accent-bg: var(--c-accent-light);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-check-circle"></i></div>
      <div class="stat-content">
        <p class="stat-label">Validés</p>
        <p class="stat-value"><?= esc($devis_validated) ?></p>
        <p class="stat-trend"><i class="fas fa-check"></i> Confirmés</p>
      </div>
    </article>
    <article class="stat-card" style="--accent: #36b9cc; --accent-bg: rgba(54,185,204,.15);">
      <div class="stat-icon" aria-hidden="true"><i class="fas fa-box"></i></div>
      <div class="stat-content">
        <p class="stat-label">Produits disponibles</p>
        <p class="stat-value"><?= esc($produits_count) ?></p>
        <p class="stat-trend"><i class="fas fa-search"></i> Catalogue</p>
      </div>
    </article>
  </section>

  <div class="dashboard-grid">
    <!-- Quick Actions -->
    <section class="card" aria-labelledby="quick-actions-heading">
      <h2 id="quick-actions-heading" class="section-title">Actions rapides</h2>
      <div class="quick-actions">
        <a href="<?= base_url('client/catalogue') ?>" class="quick-action" aria-label="Parcourir le catalogue">
          <div class="quick-action-icon" style="background: var(--c-accent-light); color: var(--c-accent);">
            <i class="fas fa-box-open" aria-hidden="true"></i>
          </div>
          <div class="quick-action-content">
            <strong>Parcourir le catalogue</strong>
            <span>Sélectionnez des produits pour votre devis</span>
          </div>
          <i class="fas fa-chevron-right quick-action-arrow" aria-hidden="true"></i>
        </a>
        <a href="<?= base_url('client/mes-devis') ?>" class="quick-action" aria-label="Voir mes devis">
          <div class="quick-action-icon" style="background: rgba(246,194,62,.15); color: #f6c23e;">
            <i class="fas fa-file-invoice" aria-hidden="true"></i>
          </div>
          <div class="quick-action-content">
            <strong>Mes devis</strong>
            <span>Suivez l'état de vos demandes</span>
          </div>
          <i class="fas fa-chevron-right quick-action-arrow" aria-hidden="true"></i>
        </a>
        <a href="<?= base_url('compte/afficher_profil') ?>" class="quick-action" aria-label="Mon profil">
          <div class="quick-action-icon" style="background: rgba(54,185,204,.15); color: #36b9cc;">
            <i class="fas fa-user-circle" aria-hidden="true"></i>
          </div>
          <div class="quick-action-content">
            <strong>Mon profil</strong>
            <span>Gérez vos informations personnelles</span>
          </div>
          <i class="fas fa-chevron-right quick-action-arrow" aria-hidden="true"></i>
        </a>
      </div>
    </section>

    <!-- My Devis Section -->
    <section class="card" aria-labelledby="my-devis-heading">
      <div class="section-header">
        <h2 id="my-devis-heading" class="section-title">Mes derniers devis</h2>
        <a href="<?= base_url('index.php/client/mes-devis') ?>" class="btn btn-ghost btn-sm">Voir tout</a>
      </div>

      <?php if (!empty($recent_devis)): ?>
        <div class="table-wrap">
          <div class="table-scroll">
            <table>
              <thead>
                <tr>
                  <th>Référence</th>
                  <th>Date</th>
                  <th>Montant</th>
                  <th>Statut</th>
                  <th style="width: 100px;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recent_devis as $devis): ?>
                  <tr>
                    <td><strong>#<?= esc($devis['dev_id']) ?></strong></td>
                    <td><?= date('d/m/Y', strtotime($devis['dev_date_creation'])) ?></td>
                    <td class="amount"><?= number_format($devis['dev_total_ttc'] ?? 0, 2, ',', ' ') ?> €</td>
                    <td>
                      <span class="badge badge-<?= ($devis['dev_statut'] ?? '') === 'validé' ? 'valid' : 'pending' ?>">
                        <?= esc(ucfirst($devis['dev_statut'] ?? 'en attente')) ?>
                      </span>
                    </td>
                    <td>
                      <div class="row-actions">
                        <a href="<?= base_url('index.php/client/devis/' . $devis['dev_id']) ?>" 
                           class="btn btn-icon btn-accent" title="Détails" aria-label="Voir le devis">
                          <i class="fas fa-eye"></i>
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
          <i class="fas fa-file-invoice" style="font-size: 3rem; color: var(--c-muted); margin-bottom: 1rem;"></i>
          <p>Aucun devis pour le moment.</p>
          <a href="<?= base_url('index.php/client/catalogue') ?>" class="btn btn-accent" style="margin-top: 1rem;">
            <i class="fas fa-plus"></i>
            Demander un devis
          </a>
        </div>
      <?php endif; ?>
    </section>

    <!-- Featured Products Section -->
    <section class="card" aria-labelledby="products-heading">
      <div class="section-header">
        <h2 id="products-heading" class="section-title">Produits à la une</h2>
        <a href="<?= base_url('index.php/produits/lister_prd') ?>" class="btn btn-ghost btn-sm">Voir le catalogue</a>
      </div>

      <?php if (!empty($featured_produits)): ?>
        <div class="products-grid">
          <?php foreach ($featured_produits as $produit): ?>
            <article class="product-card">
              <div class="product-image">
                <?php if (!empty($produit['prd_image'])) : ?>
                  <img 
                    src="<?= esc($produit['prd_image']) ?>" 
                    alt="<?= esc($produit['prd_nom']) ?>" 
                    class="product-img"
                    loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                  >
                  <div class="product-img-placeholder" style="display:none;">
                    <i class="fas fa-box"></i>
                  </div>
                <?php else: ?>
                  <div class="product-img-placeholder">
                    <i class="fas fa-box"></i>
                  </div>
                <?php endif; ?>
                <?php if (!empty($produit['prd_categorie'])): ?>
                  <span class="product-category"><?= esc($produit['prd_categorie']) ?></span>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <h3 class="product-name"><?= esc($produit['prd_nom']) ?></h3>
                <?php if (!empty($produit['prd_description'])): ?>
                  <p class="product-desc"><?= esc(mb_substr($produit['prd_description'], 0, 80)) ?>...</p>
                <?php endif; ?>
                <div class="product-footer">
                  <span class="product-price">
                    <?php if (isset($produit['prd_prix_vente'])): ?>
                      <?= number_format($produit['prd_prix_vente'], 2, ',', ' ') ?> €
                    <?php elseif (isset($produit['prd_prix'])): ?>
                      <?= number_format($produit['prd_prix'], 2, ',', ' ') ?> €
                    <?php else: ?>
                      Sur devis
                    <?php endif; ?>
                  </span>
<a href="<?= base_url('client/devis/nouveau') ?>" 
                      class="btn btn-accent btn-sm">
                     <i class="fas fa-plus"></i>
                     Devis
                   </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-box-open" style="font-size: 3rem; color: var(--c-muted); margin-bottom: 1rem;"></i>
          <p>Aucun produit disponible pour le moment.</p>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>

<style>
/* Client dashboard specific styles */
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

/* Stats Grid - Smaller, refined cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: var(--gap);
}

.stat-card {
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius);
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: transform .15s, box-shadow .15s, border-color .15s;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: var(--accent);
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,0,0,.08);
  border-color: var(--accent);
}

.stat-icon {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: var(--accent-bg);
  color: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
}

.stat-content {
  flex: 1;
  min-width: 0;
}

.stat-label {
  font-size: .75rem;
  font-weight: 500;
  color: var(--c-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
  margin: 0 0 .25rem;
}

.stat-value {
  font-family: 'DM Serif Display', Georgia, serif;
  font-size: 1.5rem;
  font-weight: 400;
  color: var(--c-ink);
  line-height: 1.2;
  margin: 0 0 .15rem;
}

.stat-trend {
  font-size: .7rem;
  color: var(--accent);
  display: flex;
  align-items: center;
  gap: .3rem;
  margin: 0;
}

.stat-trend i {
  font-size: .65rem;
}

/* Products grid */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 1rem;
}

.product-card {
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius);
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,.1);
}

.product-image {
  position: relative;
  aspect-ratio: 4/3;
  overflow: hidden;
  background: var(--c-surface);
}

.product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .3s;
}

.product-card:hover .product-img {
  transform: scale(1.05);
}

.product-img-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--c-muted);
  font-size: 2.5rem;
}

.product-category {
  position: absolute;
  top: .75rem;
  left: .75rem;
  background: var(--c-accent);
  color: #fff;
  font-size: .7rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .05em;
  padding: .25rem .5rem;
  border-radius: 4px;
}

.product-info {
  padding: 1rem;
}

.product-name {
  font-size: .95rem;
  font-weight: 600;
  color: var(--c-ink);
  margin: 0 0 .35rem;
}

.product-desc {
  font-size: .8rem;
  color: var(--c-muted);
  margin: 0 0 .75rem;
  line-height: 1.5;
}

.product-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: .75rem;
  border-top: 1px solid var(--c-border);
}

.product-price {
  font-family: 'DM Serif Display', Georgia, serif;
  font-size: 1.1rem;
  font-weight: 400;
  color: var(--c-accent);
}

.btn-sm {
  font-size: .75rem;
  padding: .4rem .75rem;
}

.quick-actions {
  display: flex;
  flex-direction: column;
  gap: .5rem;
}

.quick-action {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.25rem;
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius);
  text-decoration: none;
  color: var(--c-ink);
  transition: all .2s ease;
  position: relative;
  overflow: hidden;
}

.quick-action::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: var(--c-border);
  transition: background .2s;
}

.quick-action:hover {
  transform: translateX(4px);
  box-shadow: 0 4px 16px rgba(0,0,0,.08);
  border-color: var(--c-accent-mid);
  background: var(--c-accent-light);
}

.quick-action:hover::before {
  background: var(--c-accent);
}

.quick-action-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
  transition: transform .2s;
}

.quick-action:hover .quick-action-icon {
  transform: scale(1.05);
}

.quick-action-content {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: .2rem;
}

.quick-action-content strong {
  font-size: .95rem;
  font-weight: 600;
  color: var(--c-ink);
  line-height: 1.3;
}

.quick-action-content span {
  font-size: .8rem;
  color: var(--c-muted);
  line-height: 1.4;
}

.quick-action-arrow {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--c-surface);
  color: var(--c-muted);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .875rem;
  flex-shrink: 0;
  opacity: 0;
  transform: translateX(-8px);
  transition: all .2s;
}

.quick-action:hover .quick-action-arrow {
  opacity: 1;
  transform: translateX(0);
  background: var(--c-accent);
  color: white;
}

/* Mobile: stack as cards */
@media (max-width: 640px) {
  .quick-action {
    padding: 1rem;
  }
  .quick-action-content strong {
    font-size: .9rem;
  }
  .quick-action-content span {
    font-size: .75rem;
  }
}

/* Dashboard Grid */
@media (max-width: 900px) {
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .products-grid {
    grid-template-columns: 1fr;
  }
}
</style>