<div class="page">

    <header class="page-header">
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle"><?= count($dev) ?> devis — du plus récent au plus ancien</p>
    </header>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-error" role="alert">
            <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success" role="status">
            <i class="fas fa-circle-check" aria-hidden="true"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (empty($dev)) : ?>
        <div class="empty-state">
            <p>Aucun devis pour le moment. Créez le premier avec le formulaire ci-dessous.</p>
        </div>
    <?php else : ?>
        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Produits</th>
                            <th>Distance</th>
                            <th>Main d'œuvre</th>
                            <th>Total HT</th>
                            <th>TVA</th>
                            <th>Total TTC</th>
                            <th>État</th>
                            <th>Date création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dev as $d) : ?>
                            <tr>
                                <td>
                                    <?= esc($d['cli_nom'] ?? '—') ?>
                                    <?php if (! empty($d['cli_telephone'])) : ?>
                                        <span class="cell-sub"><?= esc($d['cli_telephone']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($d['produits'] ?? '—') ?></td>
                                <td>
                                    <?php $km = (float) ($d['dev_distance'] ?? 0); ?>
                                    <?= $km > 0 ? esc(number_format($km, 2, ',', ' ')) . ' km' : '—' ?>
                                </td>
                                <td>
                                    <form method="post"
                                          action="<?= base_url('devis/modifier_main_oeuvre/' . $d['dev_id']) ?>"
                                          class="row-actions">
                                        <input type="number" step="0.01" min="0" class="input-sm"
                                               name="dev_main_oeuvre"
                                               value="<?= esc($d['dev_main_oeuvre']) ?>"
                                               aria-label="Main d'oeuvre en TND"
                                               placeholder="0.00">
                                        <button class="btn btn-ghost btn-icon" type="submit" title="Enregistrer">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                                <td><?= esc(number_format((float) $d['dev_total_ht'], 2, ',', ' ')) ?> TND</td>
                                <td><?= esc(number_format((float) $d['dev_tva'], 2, ',', ' ')) ?> TND</td>
                                <td class="amount"><?= esc(number_format((float) $d['dev_total_ttc'], 2, ',', ' ')) ?> TND</td>
                                <td>
                                    <?php if ($d['dev_etat'] == 'P') : ?>
                                        <span class="badge badge-pending">En attente</span>
                                    <?php else : ?>
                                        <span class="badge badge-valid">Validé</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($d['dev_date_creation']) ?></td>
                                <td>
                                    <div class="row-actions">
                                        <?php if ($d['dev_etat'] == 'P') : ?>
                                            <a href="<?= base_url('devis/valider/' . $d['dev_id']) ?>" class="btn btn-accent btn-sm">
                                                Valider
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('devis/supprimer/' . $d['dev_id']) ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Supprimer définitivement ce devis ?');">
                                            Supprimer
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

    <?= view('devis/_formulaire', ['tarifKm' => $tarifKm, 'tauxTva' => $tauxTva, 'clients' => $clients, 'produits' => $produits]) ?>

</div>
