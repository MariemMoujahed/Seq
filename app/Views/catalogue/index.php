<div class="page">
    <header class="page-header">
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle">Découvrez nos solutions de sécurité</p>
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

    <!-- Search & Filters -->
    <div class="card" style="margin-bottom: var(--gap);">
        <form class="search-form" method="get" action="<?= base_url('catalogue') ?>" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <div class="search-input-wrapper" style="flex: 1; min-width: 280px; position: relative;">
                <label for="search" class="visually-hidden">Rechercher</label>
                <i class="fas fa-search" aria-hidden="true" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--c-muted); pointer-events: none;"></i>
                <input 
                    type="search" 
                    id="search" 
                    name="search" 
                    class="form-input" 
                    placeholder="Rechercher un produit, une marque..." 
                    value="<?= esc($search ?? '') ?>"
                    aria-label="Rechercher"
                    style="padding-left: 2.5rem;"
                >
                <?php if (!empty($search)) : ?>
                    <a href="<?= base_url('catalogue') ?>" class="search-clear" aria-label="Effacer la recherche" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--c-muted);">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </a>
                <?php endif; ?>
            </div>

            <div class="category-filter" style="min-width: 200px;">
                <label for="categorie" class="visually-hidden">Catégorie</label>
                <select id="categorie" name="categorie" class="form-select" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= esc($cat) ?>" <?= ($current_categorie ?? '') === $cat ? 'selected' : '' ?>>
                            <?= esc($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <noscript><button type="submit" class="btn btn-ghost btn-sm">Filtrer</button></noscript>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter" aria-hidden="true"></i> Filtrer
            </button>
        </form>
    </div>

    <!-- Products Grid -->
    <?php if (empty($produits)) : ?>
        <div class="empty-state">
            <i class="fas fa-box-open" aria-hidden="true" style="font-size: 3rem; color: var(--c-muted); margin-bottom: 1rem;"></i>
            <p>Aucun produit ne correspond à votre recherche.</p>
            <a href="<?= base_url('catalogue') ?>" class="btn btn-primary" style="margin-top: 1rem;">Voir tout le catalogue</a>
        </div>
    <?php else : ?>
        <div class="products-grid" role="list" aria-label="Produits" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--gap);">
            <?php foreach ($produits as $produit) : ?>
                <article class="product-card" role="listitem" style="background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .15s, border-color .15s;">
                    <div class="product-image" style="position: relative; aspect-ratio: 16/10; background: var(--c-surface); overflow: hidden;">
                        <?php if (!empty($produit['prd_image'])) : ?>
                            <img 
                                src="<?= esc($produit['prd_image']) ?>" 
                                alt=""
                                loading="lazy"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform .3s;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="product-image-placeholder" style="display:none; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--c-muted); font-size: 3rem;">
                                <i class="fas fa-cube" aria-hidden="true"></i>
                            </div>
                        <?php else : ?>
                            <div class="product-image-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--c-muted); font-size: 3rem;">
                                <i class="fas fa-cube" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                        <span class="product-category" style="position: absolute; top: 1rem; left: 1rem; background: rgba(0, 0, 0, 0.7); color: white; padding: .25rem .75rem; border-radius: 999px; font-size: .75rem; font-weight: 600; text-transform: uppercase;"><?= esc($produit['prd_categorie'] ?? 'Divers') ?></span>
                    </div>
                    <div class="product-content" style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 class="product-name" style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; color: var(--c-ink); margin: 0 0 .5rem;"><?= esc($produit['prd_nom']) ?></h3>
                        <?php if (!empty($produit['prd_marque'])) : ?>
                            <p class="product-brand" style="font-size: .875rem; color: var(--c-muted); margin: 0 0 .5rem;"><i class="fas fa-tag" aria-hidden="true" style="margin-right: .35rem;"></i> <?= esc($produit['prd_marque']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($produit['prd_description'])) : ?>
                            <p class="product-description" style="font-size: .875rem; color: var(--c-muted); line-height: 1.5; margin: 0 0 1rem; flex: 1;"><?= esc(mb_strimwidth($produit['prd_description'], 0, 120, '…')) ?></p>
                        <?php endif; ?>
                        <div class="product-meta" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--c-border); margin-top: auto;">
                            <span class="product-price" style="font-size: 1.125rem; font-weight: 700; color: var(--c-accent);"><?= number_format((float) $produit['prd_prix'], 2, ',', ' ') ?> €</span>
                            <?php $stock = (int) ($produit['prd_stock'] ?? 0); ?>
                            <span class="product-stock <?= $stock > 0 ? 'in-stock' : 'out-of-stock' ?>" style="display: inline-flex; align-items: center; gap: .35rem; font-size: .75rem; font-weight: 500; padding: .25rem .6rem; border-radius: 999px; <?= $stock > 0 ? 'background: var(--c-accent-light); color: var(--c-accent);' : 'background: var(--c-danger-bg); color: var(--c-danger);' ?>">
                                <i class="fas <?= $stock > 0 ? 'fa-check-circle' : 'fa-times-circle' ?>" aria-hidden="true"></i>
                                <?= $stock > 0 ? "En stock ($stock)" : 'Rupture' ?>
                            </span>
                        </div>
                    </div>
                    <div class="product-actions" style="display: flex; flex-direction: column; gap: .5rem; padding: 1.25rem; border-top: 1px solid var(--c-border); background: var(--c-surface);">
                        <a href="<?= base_url('catalogue/' . $produit['prd_id']) ?>" class="btn btn-ghost btn-block" style="justify-content: center;">
                            <i class="fas fa-eye" aria-hidden="true"></i> Détails
                        </a>
                        <a href="<?= base_url('compte/connecter?redirect=catalogue/' . $produit['prd_id']) ?>" class="btn btn-accent btn-block" style="justify-content: center;">
                            <i class="fas fa-plus" aria-hidden="true"></i> Demander un devis
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- CTA -->
    <section class="cta-section" style="text-align: center; padding: 3rem 1.5rem; background: var(--c-accent-light); border: 1px solid var(--c-accent); border-radius: var(--radius); margin-top: var(--gap);">
        <h2 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.5rem; font-weight: 400; margin-bottom: .5rem;">Besoin d'un conseil ?</h2>
        <p style="color: var(--c-muted); margin-bottom: 1.5rem;">Notre équipe commerciale vous accompagne dans le choix de vos équipements de sécurité.</p>
        <a href="<?= base_url('compte/connecter') ?>" class="btn btn-accent" style="padding: .875rem 2rem; font-size: 1rem;">
            <i class="fas fa-user-plus" aria-hidden="true"></i> Créer un compte pour demander un devis
        </a>
    </section>
</div>