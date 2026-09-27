<div class="page">

    <header class="page-header">
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle"><?= count($dev) ?> devis — du plus récent au plus ancien</p>
    </header>

    <?php if (empty($dev)) : ?>
        <div class="empty-state">
            <p>Aucun devis pour le moment. Créez le premier ci-dessous.</p>
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
                                <td><?= $d['dev_distance'] !== null ? esc($d['dev_distance']) . ' km' : '—' ?></td>
                                <td>
                                    <form method="post"
                                          action="<?= base_url('devis/modifier_main_oeuvre/' . $d['dev_id']) ?>"
                                          class="row-actions">
                                        <input type="number" step="0.01" min="0" class="input-sm"
                                               name="dev_main_oeuvre" value="<?= esc($d['dev_main_oeuvre']) ?>"
                                               aria-label="Main d'oeuvre">
                                        <button class="btn btn-ghost btn-icon" type="submit" title="Enregistrer">&check;</button>
                                    </form>
                                </td>
                                <td><?= esc($d['dev_total_ht']) ?> TND</td>
                                <td><?= esc($d['dev_tva']) ?> TND</td>
                                <td class="amount"><?= esc($d['dev_total_ttc']) ?> TND</td>
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

    <section class="card">
        <h2 class="section-title">Créer un devis</h2>

        <form method="post" action="<?= base_url('devis/creer') ?>" id="form-devis">

            <div class="field">
                <label for="cli_id">Client</label>
                <select name="cli_id" id="cli_id">
                    <option value="">— Nouveau client —</option>
                    <?php foreach ($clients as $c) : ?>
                        <option value="<?= esc($c['cli_id']) ?>">
                            <?= esc($c['cli_nom']) ?> (<?= esc($c['cli_telephone']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="nouveau-client" hidden>
                <div class="field-grid">
                    <div class="field">
                        <label for="cli_nom">Nom du client</label>
                        <input type="text" id="cli_nom" name="cli_nom" placeholder="Nom ou société" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="cli_telephone">Téléphone</label>
                        <input type="tel" id="cli_telephone" name="cli_telephone" placeholder="06 00 00 00 00" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="cli_email">Email</label>
                        <input type="email" id="cli_email" name="cli_email" placeholder="contact@exemple.tn" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="cli_region">Région</label>
                        <input type="text" id="cli_region" name="cli_region" placeholder="Tunis, Sousse…" autocomplete="off">
                    </div>
                </div>
                <div class="field" style="margin-top:1.1rem">
                    <label for="cli_adresse">Adresse</label>
                    <input type="text" id="cli_adresse" name="cli_adresse" placeholder="Adresse complète" autocomplete="off">
                </div>
            </div>

            <hr class="divider">

            <div class="field-grid">
                <div class="field">
                    <label for="dev_distance">Distance (km)</label>
                    <input type="number" step="0.01" min="0" id="dev_distance" name="dev_distance" placeholder="0.00">
                </div>
                <div class="field">
                    <label for="dev_main_oeuvre">Main d'œuvre (TND)</label>
                    <input type="number" step="0.01" min="0" id="dev_main_oeuvre" name="dev_main_oeuvre" value="0">
                </div>
            </div>

            <hr class="divider">

            <h3 class="section-title">Produits</h3>
            <div id="lignes-produits">
                <div class="product-line ligne-produit">
                    <select name="prd_id[]" aria-label="Produit">
                        <option value="">— Choisir un produit —</option>
                        <?php foreach ($produits as $p) : ?>
                            <option value="<?= esc($p['prd_id']) ?>">
                                <?= esc($p['prd_nom']) ?> — <?= esc($p['prd_marque']) ?> (<?= esc($p['prd_prix']) ?> TND)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="input-qty" name="det_quantite[]" min="1" value="1" aria-label="Quantité">
                    <button type="button" class="btn btn-ghost btn-icon btn-remove" aria-label="Retirer la ligne">&times;</button>
                </div>
            </div>

            <button type="button" id="ajouter-ligne" class="btn btn-add-line">+ Ajouter un produit</button>

            <hr class="divider">

            <button class="btn btn-primary btn-block" type="submit">Créer le devis</button>
        </form>
    </section>

</div>

<script>
    const lignes = document.getElementById('lignes-produits');

    // Empêche l'envoi si une ligne de produit est laissée vide.
    document.getElementById('form-devis').addEventListener('submit', function (e) {
        const incompletes = [...lignes.querySelectorAll('select')]
            .filter(s => !s.value);
        if (incompletes.length) {
            e.preventDefault();
            incompletes[0].focus();
            alert('Sélectionnez un produit pour chaque ligne, ou retirez les lignes vides.');
        }
    });

    document.getElementById('ajouter-ligne').addEventListener('click', function () {
        const ligne = lignes.querySelector('.ligne-produit').cloneNode(true);
        ligne.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            } else {
                el.value = 1;
            }
        });
        lignes.appendChild(ligne);
    });

    // Suppression d'une ligne (on garde toujours au moins une ligne).
    lignes.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-remove');
        if (!btn) return;
        const toutes = lignes.querySelectorAll('.ligne-produit');
        if (toutes.length === 1) {
            btn.closest('.ligne-produit').querySelectorAll('select, input')
                .forEach(el => el.tagName === 'SELECT' ? el.selectedIndex = 0 : el.value = 1);
            return;
        }
        btn.closest('.ligne-produit').remove();
    });

    // Affiche le bloc "nouveau client" uniquement quand aucun client existant n'est choisi.
    const selectClient = document.getElementById('cli_id');
    const blocNouveauClient = document.getElementById('nouveau-client');

    function toggleNouveauClient() {
        blocNouveauClient.hidden = !!selectClient.value;
    }

    selectClient.addEventListener('change', toggleNouveauClient);
    toggleNouveauClient();
</script>
