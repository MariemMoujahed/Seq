<div class="page">
    <header class="page-header">
        <nav class="breadcrumb" aria-label="Fil d'Ariane" style="display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .875rem; color: var(--c-muted); margin-bottom: 1rem;">
            <a href="<?= base_url('/') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Accueil</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <a href="<?= base_url('catalogue') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Catalogue</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <span aria-current="page" style="color: var(--c-ink); font-weight: 500;"><?= esc($produit['prd_nom']) ?></span>
        </nav>
        <h1 class="page-title"><?= esc($produit['prd_nom']) ?></h1>
        <p class="page-subtitle"><?= esc($produit['prd_categorie'] ?? 'Produit') ?> — <?= esc($produit['prd_marque'] ?? 'Marque non spécifiée') ?></p>
    </header>

    <div class="product-detail" style="display: grid; grid-template-columns: 1fr; gap: var(--gap);">
        <!-- Image Gallery -->
        <div class="product-gallery" style="position: sticky; top: 2rem;">
            <?php if (!empty($produit['prd_image'])) : ?>
                <div class="main-image" style="border-radius: var(--radius); overflow: hidden; background: var(--c-surface); aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center;">
                    <img 
                        src="<?= esc($produit['prd_image']) ?>" 
                        alt="<?= esc($produit['prd_nom']) ?>"
                        id="mainImage"
                        style="width: 100%; height: 100%; object-fit: cover;"
                    >
                </div>
            <?php else : ?>
                <div class="main-image placeholder" style="border-radius: var(--radius); background: var(--c-surface); aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center; color: var(--c-muted); font-size: 4rem;">
                    <i class="fas fa-cube" aria-hidden="true"></i>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <div class="product-meta-top" style="display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.5rem;">
                <span class="product-category-badge" style="background: var(--c-accent-light); color: var(--c-accent); padding: .5rem 1rem; border-radius: 999px; font-size: .875rem; font-weight: 600;"><?= esc($produit['prd_categorie'] ?? 'Divers') ?></span>
                <?php $stock = (int) ($produit['prd_stock'] ?? 0); ?>
                <span class="product-stock-badge <?= $stock > 0 ? 'in-stock' : 'out-of-stock' ?>" style="display: inline-flex; align-items: center; gap: .35rem; padding: .5rem 1rem; border-radius: 999px; font-size: .875rem; font-weight: 500; <?= $stock > 0 ? 'background: var(--c-accent-light); color: var(--c-accent);' : 'background: var(--c-danger-bg); color: var(--c-danger);' ?>">
                    <i class="fas <?= $stock > 0 ? 'fa-check-circle' : 'fa-times-circle' ?>" aria-hidden="true"></i>
                    <?= $stock > 0 ? "En stock ($stock disponible)" : 'Rupture de stock' ?>
                </span>
            </div>

            <div class="product-price-large" style="font-size: 2rem; font-weight: 700; color: var(--c-ink); margin-bottom: 2rem;">
                <?= number_format((float) $produit['prd_prix'], 2, ',', ' ') ?> €
                <span class="price-unit" style="font-size: 1rem; font-weight: 400; color: var(--c-muted); margin-left: .5rem;">HT / unité</span>
            </div>

            <?php if (!empty($produit['prd_description'])) : ?>
                <div class="product-description-full" style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--c-border);">
                    <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.25rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink);">Description</h3>
                    <div class="description-content" style="line-height: 1.7; color: var(--c-muted);"><?= nl2br(esc($produit['prd_description'])) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($produit['prd_caracteristiques'])) : ?>
                <div class="product-specs" style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--c-border);">
                    <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.25rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink);">Caractéristiques techniques</h3>
                    <dl class="specs-list" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <?php 
                        $specs = json_decode($produit['prd_caracteristiques'], true);
                        if (is_array($specs)) :
                            foreach ($specs as $key => $value) : ?>
                                <div class="spec-item" style="display: flex; flex-direction: column; gap: .25rem; padding: 1rem; background: var(--c-surface); border-radius: var(--radius-sm);">
                                    <dt style="font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--c-muted);"><?= esc($key) ?></dt>
                                    <dd style="font-size: .9375rem; color: var(--c-ink); margin: 0;"><?= esc($value) ?></dd>
                                </div>
                            <?php endforeach;
                        else : ?>
                            <div class="spec-item" style="display: flex; flex-direction: column; gap: .25rem; padding: 1rem; background: var(--c-surface); border-radius: var(--radius-sm);">
                                <dd style="font-size: .9375rem; color: var(--c-ink); margin: 0;"><?= nl2br(esc($produit['prd_caracteristiques'])) ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="product-actions-detail" style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="<?= base_url('compte/connecter?redirect=catalogue/' . $produit['prd_id']) ?>" 
                   class="btn btn-accent btn-block" 
                   style="padding: 1rem 1.5rem; font-size: 1rem; justify-content: center;"
                   <?= $stock <= 0 ? 'disabled aria-disabled="true" style="opacity: .5; cursor: not-allowed;"' : '' ?>>
                    <i class="fas fa-file-invoice" aria-hidden="true"></i>
                    Demander un devis pour ce produit
                </a>
                <a href="<?= base_url('catalogue') ?>" class="btn btn-ghost btn-block" style="padding: 1rem 1.5rem; font-size: 1rem; justify-content: center;">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Retour au catalogue
                </a>
            </div>
        </div>
    </div>
</div>