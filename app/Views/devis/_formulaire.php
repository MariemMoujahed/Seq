<?php
/**
 * Formulaire de création d'un devis.
 * Partagé par affichage_dev.php et affichage_dev_admin.php.
 *
 * Attend : $clients, $produits, $tarifKm, $tauxTva
 */
$tarif = (float) ($tarifKm ?? 1.5);
$tva   = (float) ($tauxTva ?? 0.19);
$mode  = old('cli_id') ? 'existant' : 'nouveau';
?>

<div class="devis-layout">

    <section class="card">
        <h2 class="section-title">Créer un devis</h2>

        <form method="post" action="<?= base_url('devis/creer') ?>" id="form-devis" novalidate>
            <?= csrf_field() ?>

            <div class="field">
                <label>Client</label>
                <div class="client-mode">
                    <label>
                        <input type="radio" name="mode_client" value="existant" id="mode-existant"
                               <?= $mode === 'existant' ? 'checked' : '' ?>>
                        Client existant
                    </label>
                    <label>
                        <input type="radio" name="mode_client" value="nouveau" id="mode-nouveau"
                               <?= $mode === 'nouveau' ? 'checked' : '' ?>>
                        Nouveau client
                    </label>
                </div>
            </div>

            <div id="bloc-client-existant" <?= $mode === 'existant' ? '' : 'hidden' ?>>
                <div class="field">
                    <label for="cli_id">Rechercher un client</label>
                    <select name="cli_id" id="cli_id">
                        <option value="">— Choisir un client —</option>
                        <?php foreach ($clients as $c) : ?>
                            <option value="<?= esc($c['cli_id']) ?>" <?= (string) old('cli_id') === (string) $c['cli_id'] ? 'selected' : '' ?>>
                                <?= esc($c['cli_nom']) ?><?= $c['cli_telephone'] ? ' — ' . esc($c['cli_telephone']) : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div id="bloc-client-nouveau" <?= $mode === 'nouveau' ? '' : 'hidden' ?>>
                <div class="field-grid">
                    <div class="field">
                        <label for="cli_nom">Nom du client <span style="color:var(--c-danger)">*</span></label>
                        <input type="text" id="cli_nom" name="cli_nom" placeholder="Nom ou société"
                               value="<?= esc(old('cli_nom')) ?>" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="cli_telephone">Téléphone</label>
                        <input type="tel" id="cli_telephone" name="cli_telephone" placeholder="06 00 00 00 00"
                               value="<?= esc(old('cli_telephone')) ?>" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="cli_region">Région</label>
                        <input type="text" id="cli_region" name="cli_region" placeholder="Sfax, Sousse…"
                               value="<?= esc(old('cli_region')) ?>" autocomplete="off">
                    </div>
                    <div class="field">
                        <label for="cli_email">Email</label>
                        <input type="email" id="cli_email" name="cli_email" placeholder="contact@exemple.tn"
                               value="<?= esc(old('cli_email')) ?>" autocomplete="off">
                    </div>
                </div>
                <div class="field">
                    <label for="cli_adresse">Adresse</label>
                    <input type="text" id="cli_adresse" name="cli_adresse" placeholder="Adresse complète"
                           value="<?= esc(old('cli_adresse')) ?>" autocomplete="off">
                </div>
            </div>

            <hr class="divider">

            <div class="field">
                <label>Produits</label>

                <div id="lignes-produits">
                    <div class="devis-line ligne-produit">
                        <span class="devis-line-thumb is-empty" aria-hidden="true">
                            <i class="fas fa-image"></i>
                        </span>
                        <select name="prd_id[]" class="js-produit" aria-label="Produit">
                            <option value="">— Choisir un produit —</option>
                            <?php foreach ($produits as $p) : ?>
                                <option value="<?= esc($p['prd_id']) ?>"
                                        data-prix="<?= esc($p['prd_prix']) ?>"
                                        data-stock="<?= esc($p['prd_stock']) ?>"
                                        data-image="<?= esc($p['prd_image'] ?? '') ?>">
                                    <?= esc($p['prd_nom']) ?> — <?= esc($p['prd_marque']) ?> (<?= esc($p['prd_prix']) ?> TND)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="input-qty js-qte" name="det_quantite[]" min="1" value="1"
                               aria-label="Quantité">
                        <span class="devis-line-total js-ligne-total">—</span>
                        <button type="button" class="btn btn-ghost btn-icon btn-remove" aria-label="Retirer la ligne">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <button type="button" id="ajouter-ligne" class="btn btn-add-line">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Ajouter un produit
                </button>
            </div>

            <hr class="divider">

            <div class="field" style="max-width:280px">
                <label for="dev_distance">Distance à couvrir (km)</label>
                <input type="number" step="0.01" min="0" id="dev_distance" name="dev_distance"
                       value="<?= esc(old('dev_distance')) ?>" placeholder="0.00"
                       data-tarif="<?= esc($tarif) ?>">
                <span class="rate-hint">
                    <i class="fas fa-route" aria-hidden="true"></i>
                    <strong><?= esc(number_format($tarif, 3, ',', ' ')) ?> TND</strong> par kilomètre
                </span>
            </div>

            <button class="btn btn-primary btn-block" type="submit">
                <i class="fas fa-file-invoice" aria-hidden="true"></i>
                Créer le devis
            </button>
        </form>
    </section>

    <aside class="devis-side">
        <div class="devis-total is-empty" id="recap">
            <h3>Récapitulatif</h3>

            <div class="devis-total-row is-sub">
                <span>Produits</span>
                <span id="r-produits">0,00 TND</span>
            </div>
            <div class="devis-total-row is-sub">
                <span>Déplacement</span>
                <span id="r-deplacement">0,00 TND</span>
            </div>
            <div class="devis-total-row is-sub">
                <span>Main d'œuvre</span>
                <span>à définir</span>
            </div>

            <div class="devis-total-sep"></div>

            <div class="devis-total-row">
                <span>Total HT</span>
                <span id="r-ht">0,00 TND</span>
            </div>
            <div class="devis-total-row is-sub">
                <span>TVA (<?= esc(round($tva * 100)) ?> %)</span>
                <span id="r-tva">0,00 TND</span>
            </div>

            <div class="devis-total-sep"></div>

            <div class="devis-total-row is-grand">
                <span>Total TTC</span>
                <span id="r-ttc">0,00 TND</span>
            </div>
        </div>
    </aside>

</div>

<script>
    (function () {
        const form       = document.getElementById('form-devis');
        const lignes     = document.getElementById('lignes-produits');
        const distance   = document.getElementById('dev_distance');
        const tauxTva    = <?= esc($tva) ?>;
        const recap      = document.getElementById('recap');

        const fmt = new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        const tnd = v => fmt.format(v) + ' TND';

        /* ---------- Bascule client existant / nouveau ---------- */

        const radios = [document.getElementById('mode-existant'), document.getElementById('mode-nouveau')];
        const blocExistant = document.getElementById('bloc-client-existant');
        const blocNouveau  = document.getElementById('bloc-client-nouveau');

        function majModeClient() {
            const nouveau = document.getElementById('mode-nouveau').checked;
            blocExistant.hidden = nouveau;
            blocNouveau.hidden  = !nouveau;
        }

        radios.forEach(r => r.addEventListener('change', majModeClient));
        majModeClient();

        /* ---------- Vignette de la ligne ---------- */

        function vignetteVide() {
            const vide = document.createElement('span');
            vide.className = 'devis-line-thumb is-empty';
            vide.setAttribute('aria-hidden', 'true');
            vide.innerHTML = '<i class="fas fa-image"></i>';
            return vide;
        }

        function majVignette(ligne) {
            const select = ligne.querySelector('.js-produit');
            if (!select) return;

            const option = select.options[select.selectedIndex];
            const image  = option ? (option.dataset.image || '') : '';

            let nouveau;
            if (image) {
                nouveau = document.createElement('img');
                // Transformation Cloudinary : carré de 88 px, remplissage centré.
                nouveau.src = image.replace('/image/upload/', '/image/upload/g_auto,w_88,h_88,c_fill/');
                nouveau.alt = '';
                nouveau.className = 'devis-line-thumb';
                nouveau.loading = 'lazy';
            } else {
                nouveau = vignetteVide();
            }

            const ancien = ligne.querySelector('.devis-line-thumb');
            if (ancien) {
                ancien.replaceWith(nouveau);
            }
        }

        function reinitialiserLigne(ligne) {
            ligne.querySelector('select').selectedIndex = 0;
            ligne.querySelector('input').value = 1;
            ligne.querySelector('.js-ligne-total').textContent = '—';
            ligne.querySelector('.js-qte').classList.remove('is-over');
            majVignette(ligne);
        }

        /* ---------- Totaux ---------- */

        function majTotaux() {
            let total = 0;
            let lignesValides = 0;

            lignes.querySelectorAll('.ligne-produit').forEach(ligne => {
                const select = ligne.querySelector('.js-produit');
                const qte    = ligne.querySelector('.js-qte');
                const affiche = ligne.querySelector('.js-ligne-total');
                const option = select.options[select.selectedIndex];

                if (!select.value) {
                    affiche.textContent = '—';
                    return;
                }

                const prix  = parseFloat(option.dataset.prix) || 0;
                const n     = Math.max(1, parseInt(qte.value, 10) || 1);
                const stock = parseInt(option.dataset.stock, 10);
                const ligneTotal = prix * n;

                total += ligneTotal;
                lignesValides++;

                // Alerte si la quantité dépasse le stock disponible
                qte.classList.toggle('is-over', !isNaN(stock) && stock < n);
                qte.setAttribute('aria-invalid', (!isNaN(stock) && stock < n) ? 'true' : 'false');

                affiche.textContent = tnd(ligneTotal);
            });

            const km      = Math.max(0, parseFloat(distance.value) || 0);
            const tarif   = parseFloat(distance.dataset.tarif) || 0;
            const deplacement = km * tarif;
            const ht      = total + deplacement;
            const tva     = ht * tauxTva;
            const ttc     = ht + tva;

            document.getElementById('r-produits').textContent    = tnd(total);
            document.getElementById('r-deplacement').textContent = tnd(deplacement);
            document.getElementById('r-ht').textContent          = tnd(ht);
            document.getElementById('r-tva').textContent         = tnd(tva);
            document.getElementById('r-ttc').textContent         = tnd(ttc);

            recap.classList.toggle('is-empty', lignesValides === 0);
        }

        form.addEventListener('input', majTotaux);
        form.addEventListener('change', majTotaux);

        // C'est ce listener qui met à jour l'image de la ligne : sans lui le
        // choix d'un produit ne changeait que les totaux, la vignette restait
        // sur le placeholder.
        lignes.addEventListener('change', function (e) {
            if (e.target.matches('.js-produit')) {
                majVignette(e.target.closest('.ligne-produit'));
            }
        });

        /* ---------- Ajout / retrait de lignes ---------- */

        document.getElementById('ajouter-ligne').addEventListener('click', function () {
            const ligne = lignes.querySelector('.ligne-produit').cloneNode(true);
            reinitialiserLigne(ligne);
            lignes.appendChild(ligne);
            majTotaux();
        });

        lignes.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-remove');
            if (!btn) return;

            const toutes = lignes.querySelectorAll('.ligne-produit');

            // On conserve toujours au moins une ligne.
            if (toutes.length === 1) {
                reinitialiserLigne(toutes[0]);
            } else {
                btn.closest('.ligne-produit').remove();
            }
            majTotaux();
        });

        /* ---------- Soumission ---------- */

        form.addEventListener('submit', function (e) {
            const alertes = [];

            if (document.getElementById('mode-nouveau').checked) {
                if (!document.getElementById('cli_nom').value.trim()) {
                    alertes.push('Saisissez le nom du nouveau client.');
                }
            } else if (!document.getElementById('cli_id').value) {
                alertes.push('Choisissez un client dans la liste.');
            }

            const incompletes = [...lignes.querySelectorAll('.js-produit')].filter(s => !s.value);
            if (incompletes.length) {
                alertes.push('Sélectionnez un produit pour chaque ligne, ou retirez les lignes vides.');
            }

            const horsStock = [...lignes.querySelectorAll('.js-qte.is-over')];
            if (horsStock.length) {
                alertes.push('Une quantité dépasse le stock disponible.');
            }

            if (alertes.length) {
                e.preventDefault();
                alert(alertes.join('\n'));
                if (incompletes.length) incompletes[0].focus();
            }
        });

        majTotaux();
    })();
</script>
