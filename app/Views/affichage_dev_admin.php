<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500&display=swap');
  :root {
    --c-ink: #1a1a2e;
    --c-surface: #f7f5f0;
    --c-card: #ffffff;
    --c-accent: #3B6D11;
    --c-accent-light: #EAF3DE;
    --c-accent-mid: #639922;
    --c-muted: #888780;
    --c-border: rgba(0,0,0,0.1);
    --c-pending: #BA7517;
    --c-pending-bg: #FAEEDA;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DM Sans', sans-serif; background: var(--c-surface); color: var(--c-ink); padding: 2rem; }
  h2 { font-family: 'DM Serif Display', serif; font-size: 2rem; font-weight: 400; color: var(--c-ink); letter-spacing: -0.02em; margin-bottom: 0.25rem; }
  h3 { font-family: 'DM Serif Display', serif; font-size: 1.3rem; font-weight: 400; margin-bottom: 1.25rem; color: var(--c-ink); }
  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  thead { background: var(--c-surface); }
  th { padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.08em; color: var(--c-muted); border-bottom: 0.5px solid var(--c-border); }
  td { padding: 12px 16px; border-bottom: 0.5px solid var(--c-border); color: var(--c-ink); vertical-align: middle; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: var(--c-surface); }
  .badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
  .badge-pending { background: var(--c-pending-bg); color: var(--c-pending); }
  .badge-valid { background: var(--c-accent-light); color: var(--c-accent); }
  .btn-valider { font-size: 12px; font-family: 'DM Sans', sans-serif; font-weight: 500; background: var(--c-ink); color: #fff; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; transition: opacity 0.15s; }
  .btn-valider:hover { opacity: 0.75; }
  .validated-text { font-size: 12px; color: var(--c-accent); font-weight: 500; }
  .amount { font-weight: 500; }
  label { display: block; font-size: 12px; font-weight: 500; color: var(--c-muted); text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 6px; }
  input[type="number"], select {
    width: 100%; padding: 9px 12px; font-family: 'DM Sans', sans-serif; font-size: 14px;
    background: var(--c-surface); border: 0.5px solid var(--c-border); border-radius: 7px;
    color: var(--c-ink); outline: none; transition: border-color 0.15s; appearance: none;
  }
  input[type="number"]:focus, select:focus { border-color: var(--c-accent-mid); }
  hr { border: none; border-top: 0.5px solid var(--c-border); margin: 2rem 0; }

  /* Tarif block */
  .tarif-block {
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    background: var(--c-card); border: 0.5px solid var(--c-border);
    border-radius: 10px; padding: 1rem 1.25rem; max-width: 380px; margin-bottom: 2rem;
  }
  .tarif-block label { margin-bottom: 0; white-space: nowrap; }
  .tarif-block input[type="number"] { width: 110px; padding: 6px 10px; font-size: 13px; }

  /* Boutons inline */
  .btn-small {
    font-size: 11px; font-family: 'DM Sans', sans-serif; font-weight: 500;
    background: var(--c-accent); color: #fff; border: none;
    padding: 5px 11px; border-radius: 5px; cursor: pointer; white-space: nowrap;
  }
  .btn-small:hover { opacity: 0.8; }

  /* Montant inline */
  .montant-form { display: flex; align-items: center; gap: 6px; }
  .montant-form input[type="number"] { width: 90px; padding: 5px 8px; font-size: 13px; }
</style>

<h2><?= esc($titre) ?></h2>
<br>

<?php if (empty($dev)) : ?>
    <p>Aucun devis trouvé.</p>
<?php else : ?>
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
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dev as $d) : ?>
                <tr>
                    <td>
                        <?= esc($d['cli_nom'] ?? '—') ?>
                        <?php if (! empty($d['cli_telephone'])) : ?>
                            <br><small><?= esc($d['cli_telephone']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($d['produits'] ?? '—') ?></td>
                    <td><?= $d['dev_distance'] !== null ? esc($d['dev_distance']) . ' km' : '—' ?></td>
                    <td>
                        <form method="post" action="<?= base_url('devis/modifier_main_oeuvre/' . $d['dev_id']) ?>" style="display:inline">
                            <input type="number" step="0.01" name="dev_main_oeuvre" value="<?= esc($d['dev_main_oeuvre']) ?>" style="width:90px">
                            <button class="btn-submit" type="submit">✔</button>
                        </form>
                    </td>
                    <td><?= esc($d['dev_total_ht']) ?> €</td>
                    <td><?= esc($d['dev_tva']) ?> €</td>
                    <td class="amount"><strong><?= esc($d['dev_total_ttc']) ?> €</strong></td>
                    <td>
                        <?php if ($d['dev_etat'] == 'P') : ?>
                            <span class="badge badge-pending">⏳ En attente</span>
                        <?php else : ?>
                            <span class="badge badge-valid">✔ Validé</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($d['dev_date_creation']) ?></td>
                    <td>
                        <?php if ($d['dev_etat'] == 'P') : ?>
                            <a href="<?= base_url('/devis/valider/' . $d['dev_id']) ?>" class="btn-submit">Valider</a>
                        <?php endif; ?>
                        <a href="<?= base_url('/devis/supprimer/' . $d['dev_id']) ?>"
                           onclick="return confirm('Tu es sûr de vouloir supprimer ce devis ?');"
                           class="btn-delete">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<hr>

<h3>Créer un devis</h3>
<form method="post" action="<?= base_url('devis/creer') ?>" id="form-devis">

    <label>Client :</label>
    <select name="cli_id" id="cli_id">
        <option value="">-- Nouveau client --</option>
        <?php foreach ($clients as $c) : ?>
            <option value="<?= esc($c['cli_id']) ?>"><?= esc($c['cli_nom']) ?> (<?= esc($c['cli_telephone']) ?>)</option>
        <?php endforeach; ?>
    </select>

    <div id="nouveau-client">
        <label>Nom du client :</label>
        <input type="text" name="cli_nom" placeholder="Nom / société">

        <label>Téléphone :</label>
        <input type="text" name="cli_telephone" placeholder="Téléphone">

        <label>Email :</label>
        <input type="email" name="cli_email" placeholder="Email">

        <label>Adresse :</label>
        <input type="text" name="cli_adresse" placeholder="Adresse">

        <label>Région :</label>
        <input type="text" name="cli_region" placeholder="Région">
    </div>

    <br><br>
    <label>Distance (km) :</label>
    <input type="number" step="0.01" name="dev_distance">

    <label>Main d'œuvre (€) :</label>
    <input type="number" step="0.01" name="dev_main_oeuvre" value="0">

    <hr>
    <h4>Produits</h4>
    <div id="lignes-produits">
        <div class="ligne-produit">
            <select name="prd_id[]">
                <option value="">-- Choisir un produit --</option>
                <?php foreach ($produits as $p) : ?>
                    <option value="<?= esc($p['prd_id']) ?>">
                        <?= esc($p['prd_nom']) ?> — <?= esc($p['prd_marque']) ?> (<?= esc($p['prd_prix']) ?> €)
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="det_quantite[]" min="1" value="1" style="width:70px">
        </div>
    </div>
    <button type="button" id="ajouter-ligne">+ Ajouter un produit</button>

    <br><br>
    <button class="btn-submit" type="submit">Créer le devis</button>
</form>

<script>
document.getElementById('ajouter-ligne').addEventListener('click', function () {
    const conteneur = document.getElementById('lignes-produits');
    const ligne = conteneur.querySelector('.ligne-produit').cloneNode(true);
    ligne.querySelectorAll('select, input').forEach(el => {
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
        if (el.tagName === 'INPUT') el.value = 1;
    });
    conteneur.appendChild(ligne);
});

// Masque le formulaire "nouveau client" si un client existant est choisi
const selectClient = document.getElementById('cli_id');
const blocNouveauClient = document.getElementById('nouveau-client');
function toggleNouveauClient() {
    blocNouveauClient.style.display = selectClient.value ? 'none' : 'block';
}
selectClient.addEventListener('change', toggleNouveauClient);
toggleNouveauClient();
</script>
