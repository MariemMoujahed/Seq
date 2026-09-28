<div class="page">
    <header class="page-header">
        <nav class="breadcrumb" aria-label="Fil d'Ariane" style="display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .875rem; color: var(--c-muted); margin-bottom: 1rem;">
            <a href="<?= base_url('compte/accueil') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Tableau de bord</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <span aria-current="page" style="color: var(--c-ink); font-weight: 500;"><?= esc($titre) ?></span>
        </nav>
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle">Suivez l'état de vos demandes de devis</p>
    </header>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="status">
            <i class="fas fa-circle-check" aria-hidden="true"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-error" role="alert">
            <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <!-- Stats Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--gap); margin-bottom: var(--gap);">
        <div class="card" style="border-left: 4px solid var(--c-accent);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--c-accent-light); color: var(--c-accent); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <p style="font-size: .875rem; color: var(--c-muted); margin: 0 0 .25rem;">Total devis</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin: 0;"><?= count($devis) ?></p>
                </div>
            </div>
        </div>
        <div class="card" style="border-left: 4px solid var(--c-pending);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--c-pending-bg); color: var(--c-pending); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p style="font-size: .875rem; color: var(--c-muted); margin: 0 0 .25rem;">En attente</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin: 0;"><?= count(array_filter($devis, fn($d) => ($d['dev_etat'] ?? '') === 'P')) ?></p>
                </div>
            </div>
        </div>
        <div class="card" style="border-left: 4px solid var(--c-accent);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--c-accent-light); color: var(--c-accent); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p style="font-size: .875rem; color: var(--c-muted); margin: 0 0 .25rem;">Validés</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin: 0;"><?= count(array_filter($devis, fn($d) => ($d['dev_etat'] ?? '') === 'V')) ?></p>
                </div>
            </div>
        </div>
        <div class="card" style="border-left: 4px solid var(--c-accent-mid);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= base_url('client/catalogue') ?>" class="btn btn-accent btn-sm" style="text-decoration:none; padding: .5rem 1rem; font-size: .875rem; width: 100%; justify-content: center;">
                    <i class="fas fa-plus" aria-hidden="true"></i> Nouveau devis
                </a>
            </div>
        </div>
    </div>

    <!-- Devis List -->
    <?php if (empty($devis)) : ?>
        <div class="empty-state">
            <i class="fas fa-file-invoice" aria-hidden="true" style="font-size: 3rem; color: var(--c-muted); margin-bottom: 1rem;"></i>
            <p>Aucune demande de devis pour le moment.</p>
            <a href="<?= base_url('client/catalogue') ?>" class="btn btn-accent" style="margin-top: 1rem;">
                <i class="fas fa-plus" aria-hidden="true"></i> Créer mon premier devis
            </a>
        </div>
    <?php else : ?>
        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Produits</th>
                            <th>Total TTC</th>
                            <th>Statut</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devis as $d) : ?>
                            <tr>
                                <td>
                                    <strong><?= esc($d['dev_id'] ?? 'DEV-' . str_pad($d['dev_id'] ?? 0, 6, '0', STR_PAD_LEFT)) ?></strong>
                                </td>
                                <td><?= date('d/m/Y', strtotime($d['dev_date_creation'] ?? $d['dev_date'] ?? '')) ?></td>
                                <td><?= esc($d['cli_nom'] ?? '—') ?></td>
                                <td>
                                    <span style="display: inline-block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;">
                                        <?= esc(mb_strimwidth($d['produits'] ?? '—', 0, 50, '…')) ?>
                                    </span>
                                </td>
                                <td class="amount"><?= number_format((float) ($d['dev_total_ttc'] ?? 0), 2, ',', ' ') ?> €</td>
                                <td>
                                    <?php if (($d['dev_etat'] ?? '') === 'P') : ?>
                                        <span class="badge badge-pending"><i class="fas fa-clock" aria-hidden="true"></i> En attente</span>
                                    <?php else : ?>
                                        <span class="badge badge-valid"><i class="fas fa-check-circle" aria-hidden="true"></i> Validé</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="<?= base_url('client/devis/' . $d['dev_id']) ?>" 
                                           class="btn btn-icon btn-accent" title="Voir le détail" aria-label="Voir le devis #<?= $d['dev_id'] ?>">
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
    <?php endif; ?>
</div>