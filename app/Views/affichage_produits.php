<style>
/* =========================
   PAGE CATALOGUE PRODUITS
   ========================= */

h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #1f2937;
    font-weight: 700;
}

h3 {
    font-size: 22px;
    margin: 30px 0 18px;
    color: #1f2937;
}

/* Messages */
.badge {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 14px;
    font-weight: 600;
}

.badge-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffe69c;
}

.badge-valid {
    background: #d1e7dd;
    color: #0f5132;
    border: 1px solid #a3cfbb;
}

/* Tableau */
table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    margin-top: 15px;
}

thead {
    background: #1f2937;
    color: white;
}

th {
    padding: 15px 14px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
}

td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
    font-size: 14px;
}

tbody tr {
    transition: background 0.2s ease;
}

tbody tr:hover {
    background: #f8fafc;
}

tbody tr:last-child td {
    border-bottom: none;
}

/* Formulaire modification prix / stock */
td form {
    display: flex;
    gap: 8px;
    align-items: center;
    margin: 0;
}

td form input {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px;
    font-size: 14px;
    outline: none;
    transition: 0.2s;
}

td form input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

/* Bouton général */
.btn-submit {
    border: none;
    background: #2563eb;
    color: white;
    padding: 9px 16px;
    border-radius: 7px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-submit:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

/* Petit bouton validation */
td .btn-submit {
    width: 38px;
    height: 36px;
    padding: 0;
    font-size: 17px;
}

/* Bouton supprimer */
.btn-delete {
    display: inline-block;
    background: #dc2626;
    color: white;
    text-decoration: none;
    padding: 8px 13px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-delete:hover {
    background: #b91c1c;
    transform: translateY(-1px);
}

/* Séparation */
hr {
    border: 0;
    border-top: 1px solid #e5e7eb;
    margin: 35px 0;
}

/* =========================
   FORMULAIRE AJOUT
   ========================= */

form[action*="produits/ajouter"] {
    max-width: 650px;
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
}

form[action*="produits/ajouter"] label {
    display: block;
    margin-top: 15px;
    margin-bottom: 7px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

form[action*="produits/ajouter"] input {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    background: #f9fafb;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
}

form[action*="produits/ajouter"] input:focus {
    background: white;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

form[action*="produits/ajouter"] .btn-submit {
    margin-top: 5px;
    padding: 11px 22px;
}

/* =========================
   RESPONSIVE
   ========================= */

@media (max-width: 900px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    th,
    td {
        padding: 11px;
    }
}

@media (max-width: 600px) {
    h2 {
        font-size: 23px;
    }

    h3 {
        font-size: 19px;
    }

    form[action*="produits/ajouter"] {
        padding: 18px;
    }
}
</style>
<h2><?= esc($titre) ?></h2>
<br>

<?php if (session()->getFlashdata('error')) : ?>
    <p class="badge badge-pending"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>
<?php if (session()->getFlashdata('success')) : ?>
    <p class="badge badge-valid"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<?php if (empty($produits)) : ?>
    <p>Aucun produit dans le catalogue.</p>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Marque</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $p) : ?>
                <tr>
                    <td><?= esc($p['prd_nom']) ?></td>
                    <td><?= esc($p['prd_marque']) ?></td>
                    <td><?= esc($p['prd_categorie']) ?></td>
                    <td colspan="2">
                        <form method="post" action="<?= base_url('produits/modifier/' . $p['prd_id']) ?>" style="display:flex; gap:8px; align-items:center">
                            <input type="number" step="0.01" name="prd_prix" value="<?= esc($p['prd_prix']) ?>" style="width:90px"> TND
                            <input type="number" name="prd_stock" value="<?= esc($p['prd_stock']) ?>" style="width:70px"> en stock
                            <button class="btn-submit" type="submit">✔</button>
                        </form>
                    </td>
                    <td>
                        <a href="<?= base_url('/produits/supprimer/' . $p['prd_id']) ?>"
                           onclick="return confirm('Supprimer ce produit du catalogue ?');"
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

<h3>Ajouter un produit</h3>
<form method="post" action="<?= base_url('produits/ajouter') ?>">
    <label>Nom :</label>
    <input type="text" name="prd_nom" placeholder="Nom du produit" required>

    <label>Marque :</label>
    <input type="text" name="prd_marque" placeholder="Marque">

    <label>Catégorie :</label>
    <input type="text" name="prd_categorie" placeholder="Ex : Caméra, Alarme, Contrôle d'accès">

    <label>Prix (TND) :</label>
    <input type="number" step="0.01" name="prd_prix" required>

    <label>Stock :</label>
    <input type="number" name="prd_stock" value="0">

    <br><br>
    <button class="btn-submit" type="submit">Ajouter</button>
</form>
