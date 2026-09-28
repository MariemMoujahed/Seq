<div class="page">
    <header class="page-header">
        <nav class="breadcrumb" aria-label="Fil d'Ariane" style="display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .875rem; color: var(--c-muted); margin-bottom: 1rem;">
            <a href="<?= base_url('compte/accueil') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Tableau de bord</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <a href="<?= base_url('client/mes-devis') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Mes Devis</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <span aria-current="page" style="color: var(--c-ink); font-weight: 500;">Devis #<?= $devis['dev_id'] ?></span>
        </nav>
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-top: .5rem;">
            <h1 class="page-title" style="margin: 0;">Devis #<?= $devis['dev_id'] ?></h1>
            <div style="display: flex; gap: .5rem;">
                <?php if (($devis['dev_etat'] ?? '') === 'P') : ?>
                    <span class="badge badge-pending"><i class="fas fa-clock" aria-hidden="true"></i> En attente</span>
                <?php else : ?>
                    <span class="badge badge-valid"><i class="fas fa-check-circle" aria-hidden="true"></i> Validé</span>
                <?php endif; ?>
            </div>
        </div>
        <p class="page-subtitle">Créé le <?= date('d/m/Y', strtotime($devis['dev_date_creation'] ?? '')) ?></p>
    </header>

    <div class="devis-layout" style="display: grid; grid-template-columns: 1fr; gap: var(--gap);">
        <!-- Main Content -->
        <main class="devis-main">
            <!-- Client Info -->
            <section class="card" style="margin-bottom: var(--gap);">
                <h2 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink); padding-bottom: 1rem; border-bottom: 1px solid var(--c-border);"><i class="fas fa-user" aria-hidden="true" style="color: var(--c-accent); margin-right: .5rem;"></i>Informations Client</h2>
                <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.1rem;">
                    <div class="field" style="margin-bottom: 0;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px;">Nom</label>
                        <span style="color: var(--c-ink);"><?= esc($devis['cli_nom'] ?? '—') ?></span>
                    </div>
                    <div class="field" style="margin-bottom: 0;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px;">Téléphone</label>
                        <span style="color: var(--c-ink);"><?= esc($devis['cli_telephone'] ?? '—') ?></span>
                    </div>
                    <div class="field" style="margin-bottom: 0;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px;">Email</label>
                        <span style="color: var(--c-ink);"><?= esc($devis['cli_email'] ?? '—') ?></span>
                    </div>
                    <div class="field" style="margin-bottom: 0;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px;">Adresse</label>
                        <span style="color: var(--c-ink);"><?= esc($devis['cli_adresse'] ?? '—') ?></span>
                    </div>
                    <?php if (!empty($devis['cli_region'])) : ?>
                        <div class="field" style="margin-bottom: 0;">
                            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--c-muted); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px;">Région</label>
                            <span style="color: var(--c-ink);"><?= esc($devis['cli_region']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Products Lines -->
            <section class="card" style="margin-bottom: var(--gap);">
                <h2 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink); padding-bottom: 1rem; border-bottom: 1px solid var(--c-border);"><i class="fas fa-boxes" aria-hidden="true" style="color: var(--c-accent); margin-right: .5rem;"></i>Détail des produits</h2>
                
                <?php if (empty($lignes)) : ?>
                    <p style="color: var(--c-muted);">Aucun produit dans ce devis.</p>
                <?php else : ?>
                    <div class="table-wrap">
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th style="width: 100px;">Qté</th>
                                        <th style="width: 150px;">Prix unitaire</th>
                                        <th style="width: 150px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lignes as $ligne) : 
                                        $totalLigne = $ligne['det_prix'];
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="prd-cell-product" style="display: flex; align-items: center; gap: .8rem; min-width: 210px;">
                                                    <?php if (!empty($ligne['prd_image'])) : ?>
                                                        <img src="<?= esc($ligne['prd_image']) ?>" 
                                                             alt="" class="prd-thumb" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid var(--c-border); background: var(--c-surface);">
                                                    <?php else : ?>
                                                        <div class="prd-thumb-empty" style="width: 52px; height: 52px; border-radius: 8px; border: 1px dashed var(--c-border); background: var(--c-surface); display: flex; align-items: center; justify-content: center; color: #b9b8b1; font-size: 1.1rem;"><i class="fas fa-cube"></i></div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="prd-cell-name" style="font-weight: 500;"><?= esc($ligne['prd_nom'] ?? 'Produit supprimé') ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" style="text-align: center;"><?= $ligne['det_quantite'] ?></td>
                                            <td class="text-right" style="text-align: right;"><?= number_format($totalLigne / $ligne['det_quantite'], 2, ',', ' ') ?> €</td>
                                            <td class="text-right font-weight-bold" style="text-align: right; font-weight: 700;"><?= number_format($totalLigne, 2, ',', ' ') ?> €</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Totals -->
            <section class="card" style="margin-bottom: var(--gap);">
                <h2 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink); padding-bottom: 1rem; border-bottom: 1px solid var(--c-border);"><i class="fas fa-calculator" aria-hidden="true" style="color: var(--c-accent); margin-right: .5rem;"></i>Récapitulatif financier</h2>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: var(--gap);">
                    <div class="devis-total" style="background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); padding: 1.25rem 1.35rem;">
                        <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.05rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink);">Détail</h3>
                        
                        <div class="devis-total-row" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .875rem; color: var(--c-muted);">
                            <span>Total produits</span>
                            <span style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;"><?= number_format((float) ($devis['dev_total_ht'] ?? 0) - (float) ($devis['dev_distance'] ?? 0) * (float) env('devis.tarifKilometre', 1.5), 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="devis-total-row is-sub" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .8rem; opacity: .9; color: var(--c-muted);">
                            <span>Frais de déplacement</span>
                            <span style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;"><?= number_format((float) ($devis['dev_distance'] ?? 0), 2, ',', ' ') ?> km × <?= number_format((float) env('devis.tarifKilometre', 1.5), 2, ',', ' ') ?> €/km</span>
                        </div>
                        <div class="devis-total-sep" style="height: 1px; background: var(--c-border); margin: .6rem 0;"></div>
                        <div class="devis-total-row" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .875rem; color: var(--c-muted);">
                            <span>= Total HT</span>
                            <span style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;"><?= number_format((float) ($devis['dev_total_ht'] ?? 0), 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="devis-total-row is-sub" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .8rem; opacity: .9; color: var(--c-muted);">
                            <span>TVA (19%)</span>
                            <span style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;"><?= number_format((float) ($devis['dev_tva'] ?? 0), 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="devis-total-sep" style="height: 1px; background: var(--c-border); margin: .6rem 0;"></div>
                        <div class="devis-total-row is-grand" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: 1rem; color: var(--c-ink); padding-top: .2rem;">
                            <span>Total TTC</span>
                            <span style="font-size: 1.35rem; font-weight: 700; color: var(--c-accent); white-space: nowrap;"><?= number_format((float) ($devis['dev_total_ttc'] ?? 0), 2, ',', ' ') ?> €</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem; min-width: 200px;">
                        <a href="<?= base_url('client/mes-devis') ?>" class="btn btn-ghost btn-block" style="justify-content: center;">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i> Retour à la liste
                        </a>
                        <?php if (($devis['dev_etat'] ?? '') === 'V') : ?>
                            <button class="btn btn-accent btn-block" disabled style="justify-content: center;">
                                <i class="fas fa-check" aria-hidden="true"></i> Devis validé
                            </button>
                        <?php else : ?>
                            <p style="text-align: center; color: var(--c-muted); margin-top: .5rem;">
                                <i class="fas fa-info-circle" aria-hidden="true"></i> En attente de validation par notre équipe commerciale.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>

        <!-- Sidebar -->
        <aside class="devis-side" style="position: sticky; top: 2rem;">
            <div class="card" style="margin-bottom: var(--gap);">
                <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink);">Référence</h3>
                <div style="display: flex; flex-direction: column; gap: .75rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: .75rem; border-bottom: 1px solid var(--c-border);">
                        <span style="font-size: .875rem; color: var(--c-muted);">Numéro</span>
                        <span style="font-weight: 500; color: var(--c-ink); text-align: right;">#<?= $devis['dev_id'] ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: .75rem; border-bottom: 1px solid var(--c-border);">
                        <span style="font-size: .875rem; color: var(--c-muted);">Date</span>
                        <span style="font-weight: 500; color: var(--c-ink); text-align: right;"><?= date('d/m/Y', strtotime($devis['dev_date_creation'] ?? '')) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: .75rem; border-bottom: 1px solid var(--c-border);">
                        <span style="font-size: .875rem; color: var(--c-muted);">Commercial</span>
                        <span style="font-weight: 500; color: var(--c-ink); text-align: right;"><?= esc($devis['cpt_prenom'] ?? '') ?> <?= esc($devis['cpt_nom'] ?? '') ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: .875rem; color: var(--c-muted);">Statut</span>
                        <span style="font-weight: 500; color: var(--c-ink); text-align: right;">
                            <?php if (($devis['dev_etat'] ?? '') === 'P') : ?>
                                <span class="badge badge-pending">En attente</span>
                            <?php else : ?>
                                <span class="badge badge-valid">Validé</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink);">Actions</h3>
                <div style="display: flex; flex-direction: column; gap: .75rem;">
                    <a href="<?= base_url('client/mes-devis') ?>" class="btn btn-ghost btn-block" style="justify-content: center;">
                        <i class="fas fa-list" aria-hidden="true"></i> Tous mes devis
                    </a>
                    <a href="<?= base_url('client/catalogue') ?>" class="btn btn-accent btn-block" style="justify-content: center;">
                        <i class="fas fa-plus" aria-hidden="true"></i> Nouveau devis
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>