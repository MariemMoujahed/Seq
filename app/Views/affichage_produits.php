<?php use App\Libraries\CloudinaryService; ?>

<div class="page">

    <header class="page-header">
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle"><?= count($produits) ?> produit<?= count($produits) > 1 ? 's' : '' ?> au catalogue</p>
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

    <?php if (empty($produits)) : ?>
        <div class="empty-state">
            <p>Aucun produit dans le catalogue. Ajoutez le premier ci-dessous.</p>
        </div>
    <?php else : ?>
        <div class="table-wrap">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Prix &amp; stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $p) : ?>
                            <tr>
                                <td>
                                    <div class="prd-cell-product">
                                        <?php if (! empty($p['prd_image'])) : ?>
                                            <img class="prd-thumb"
                                                 src="<?= esc(CloudinaryService::thumbnail($p['prd_image'], 104, 104)) ?>"
                                                 alt="<?= esc($p['prd_nom']) ?>"
                                                 loading="lazy"
                                                 width="52" height="52">
                                        <?php else : ?>
                                            <span class="prd-thumb-empty" aria-hidden="true">
                                                <i class="fas fa-image"></i>
                                            </span>
                                        <?php endif; ?>

                                        <span>
                                            <span class="prd-cell-name"><?= esc($p['prd_nom']) ?></span>
                                            <?php if (! empty($p['prd_marque'])) : ?>
                                                <span class="prd-cell-meta"><?= esc($p['prd_marque']) ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </td>

                                <td><?= esc($p['prd_categorie'] ?: '—') ?></td>

                                <td>
                                    <?= form_open_multipart('produits/modifier/' . $p['prd_id'], ['class' => 'row-actions']) ?>
                                        <input type="number" step="0.01" min="0" class="input-sm"
                                               name="prd_prix" value="<?= esc($p['prd_prix']) ?>"
                                               aria-label="Prix en TND">
                                        <input type="number" name="prd_stock" class="input-sm"
                                               value="<?= esc($p['prd_stock']) ?>"
                                               aria-label="Stock">
                                        <input type="file" name="prd_image" class="input-file-sm"
                                               accept=".jpg,.jpeg,.png,.webp,.gif"
                                               aria-label="Remplacer l'image">
                                        <button class="btn btn-primary btn-icon" type="submit" title="Enregistrer">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                        </button>
                                    <?= form_close() ?>
                                </td>

                                <td>
                                    <a href="<?= base_url('/produits/supprimer/' . $p['prd_id']) ?>"
                                       onclick="return confirm('Supprimer ce produit du catalogue ?');"
                                       class="btn btn-danger btn-sm">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <section class="card">
        <h2 class="section-title">Ajouter un produit</h2>

        <?= form_open_multipart('produits/ajouter') ?>
        <?= csrf_field() ?>

        <div class="field-grid">
            <div class="field">
                <label for="prd_nom">Nom du produit</label>
                <input type="text" id="prd_nom" name="prd_nom" placeholder="Ex : Caméra IP 4MP" required>
            </div>

            <div class="field">
                <label for="prd_marque">Marque</label>
                <input type="text" id="prd_marque" name="prd_marque" placeholder="Ex : Hikvision" autocomplete="off">
            </div>
        </div>

        <div class="field-grid">
            <div class="field">
                <label for="prd_categorie">Catégorie</label>
                <input type="text" id="prd_categorie" name="prd_categorie" placeholder="Ex : Caméra, Alarme, Interphone">
            </div>

            <div class="field">
                <label for="prd_prix">Prix (TND)</label>
                <input type="number" step="0.01" min="0" id="prd_prix" name="prd_prix" placeholder="0.00" required>
            </div>

            <div class="field">
                <label for="prd_stock">Stock</label>
                <input type="number" min="0" id="prd_stock" name="prd_stock" value="0">
            </div>
        </div>

        <div class="field">
            <label>Image du produit</label>
            <div class="upload-zone">
                <img class="upload-preview" id="apercu" alt="Aperçu de l'image sélectionnée">

                <div class="upload-field">
                    <input type="file" class="upload-input" id="prd_image"
                           name="prd_image" accept=".jpg,.jpeg,.png,.webp,.gif">
                    <label class="upload-trigger" for="prd_image" id="trigger-libelle">
                        <i class="fas fa-cloud-arrow-up" aria-hidden="true"></i>
                        <span>Choisir une image</span>
                    </label>
                    <p class="upload-hint">JPEG, PNG, WebP ou GIF — 5 Mo maximum. Envoyée sur Cloudinary.</p>
                </div>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Ajouter le produit
        </button>
        <?= form_close() ?>
    </section>

</div>

<script>
    // Aperçu de l'image avant envoi
    (function () {
        const input = document.getElementById('prd_image');
        const apercu = document.getElementById('apercu');
        const trigger = document.getElementById('trigger-libelle');
        const libelle = trigger.querySelector('span');
        const TAILLES = 5 * 1024 * 1024;
        const TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (!input) return;

        input.addEventListener('change', function () {
            const fichier = input.files && input.files[0];

            if (!fichier) {
                apercu.style.display = 'none';
                apercu.removeAttribute('src');
                trigger.classList.remove('is-set');
                libelle.textContent = 'Choisir une image';
                return;
            }

            // Contrôle miroir de la validation côté serveur
            if (TYPES.indexOf(fichier.type) === -1) {
                alert('Format non autorisé. Utilisez JPEG, PNG, WebP ou GIF.');
                input.value = '';
                return;
            }
            if (fichier.size > TAILLES) {
                alert('Image trop lourde : 5 Mo maximum.');
                input.value = '';
                return;
            }

            const lecteur = new FileReader();
            lecteur.onload = function (e) {
                apercu.src = e.target.result;
                apercu.style.display = 'block';
            };
            lecteur.readAsDataURL(fichier);

            trigger.classList.add('is-set');
            libelle.textContent = fichier.name;
        });
    })();
</script>
