<div class="page">
    <header class="page-header">
        <nav class="breadcrumb" aria-label="Fil d'Ariane" style="display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .875rem; color: var(--c-muted); margin-bottom: 1rem;">
            <a href="<?= base_url('compte/accueil') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Tableau de bord</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <a href="<?= base_url('client/catalogue') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Catalogue</a>
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

            <!-- Add to Quote Form -->
            <div class="product-actions-detail" style="display: flex; flex-direction: column; gap: 1rem;">
                <form id="addToQuoteForm" class="add-to-quote-form" style="background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius); padding: 1.5rem;">
                    <input type="hidden" name="prd_id" value="<?= $produit['prd_id'] ?>">
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="quantity" class="form-label" style="display: block; font-weight: 500; margin-bottom: .5rem; color: var(--c-ink); font-size: .875rem;">Quantité</label>
                        <div class="quantity-input-group" style="display: flex; align-items: center; gap: .5rem;">
                            <button type="button" class="btn btn-icon qty-btn qty-decrease" aria-label="Diminuer" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; padding: 0; border: 1px solid var(--c-border); background: var(--c-card); border-radius: var(--radius-sm);">
                                <i class="fas fa-minus" aria-hidden="true"></i>
                            </button>
                            <input type="number" id="quantity" name="quantity" class="form-input qty-input" 
                                   value="1" min="1" max="<?= $stock > 0 ? $stock : 999 ?>" 
                                   aria-label="Quantité"
                                   style="width: 100px; text-align: center; -moz-appearance: textfield;"
                                   <?= $stock <= 0 ? 'disabled' : '' ?>>
                            <button type="button" class="btn btn-icon qty-btn qty-increase" 
                                    <?= $stock > 0 && $stock <= 1 ? 'disabled' : '' ?>
                                    aria-label="Augmenter"
                                    style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; padding: 0; border: 1px solid var(--c-border); background: var(--c-card); border-radius: var(--radius-sm); <?= $stock > 0 && $stock <= 1 ? 'opacity: .5; cursor: not-allowed;' : '' ?>">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                        <?php if ($stock > 0) : ?>
                            <p class="form-hint" style="font-size: .8125rem; color: var(--c-muted); margin-top: .5rem;">Stock disponible : <?= $stock ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="quote-summary" style="background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); padding: 1.25rem; margin-bottom: 1.5rem;">
                        <div class="summary-row" style="display: flex; justify-content: space-between; padding: .5rem 0; color: var(--c-muted);">
                            <span>Prix unitaire</span>
                            <span id="unitPrice" style="color: var(--c-ink); font-weight: 500;"><?= number_format((float) $produit['prd_prix'], 2, ',', ' ') ?> €</span>
                        </div>
                        <div class="summary-row total" style="display: flex; justify-content: space-between; padding: .5rem 0; border-top: 1px solid var(--c-border); margin-top: .5rem; padding-top: 1rem; font-weight: 600; font-size: 1.125rem; color: var(--c-ink);">
                            <span>Total</span>
                            <span id="totalPrice" style="color: var(--c-accent);"><?= number_format((float) $produit['prd_prix'], 2, ',', ' ') ?> €</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent btn-block" 
                            id="addToQuoteBtn"
                            style="padding: 1rem 1.5rem; font-size: 1rem; justify-content: center;"
                            <?= $stock <= 0 ? 'disabled' : '' ?>>
                        <i class="fas fa-plus" aria-hidden="true"></i>
                        <span class="btn-text" style="display: inline;">Ajouter au devis</span>
                        <span class="btn-loading" style="display: none; align-items: center; gap: .5rem;">
                            <i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Ajout...
                        </span>
                    </button>
                </form>

                <a href="<?= base_url('client/catalogue') ?>" class="btn btn-ghost btn-block" style="padding: 1rem 1.5rem; font-size: 1rem; justify-content: center;">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Retour au catalogue
                </a>
            </div>
        </div>
    </div>

<style>
.qty-input {
    -moz-appearance: textfield;
}
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<script>
// Quantity controls
const qtyInput = document.getElementById('quantity');
const decreaseBtn = document.querySelector('.qty-decrease');
const increaseBtn = document.querySelector('.qty-increase');
const totalPriceEl = document.getElementById('totalPrice');
const unitPrice = parseFloat(document.getElementById('unitPrice').textContent.replace(/[^\d.,]/g, '').replace(',', '.'));

function updateTotal() {
    const qty = parseInt(qtyInput.value) || 1;
    const total = qty * unitPrice;
    totalPriceEl.textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
}

decreaseBtn?.addEventListener('click', () => {
    const val = parseInt(qtyInput.value) || 1;
    if (val > 1) {
        qtyInput.value = val - 1;
        updateTotal();
    }
});

increaseBtn?.addEventListener('click', () => {
    const val = parseInt(qtyInput.value) || 1;
    const max = parseInt(qtyInput.max) || 999;
    if (val < max) {
        qtyInput.value = val + 1;
        updateTotal();
    }
});

qtyInput?.addEventListener('change', () => {
    let val = parseInt(qtyInput.value) || 1;
    const max = parseInt(qtyInput.max) || 999;
    val = Math.max(1, Math.min(val, max));
    qtyInput.value = val;
    updateTotal();
});

// Form submit
document.getElementById('addToQuoteForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('addToQuoteBtn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoading = btn.querySelector('.btn-loading');
    
    const formData = new FormData(this);
    
    btn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline-flex';
    
    try {
        const res = await fetch('<?= base_url('catalogue/ajouterAuDevis') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        });
        
        const data = await res.json();
        
        if (data.success) {
            showToast(`${qtyInput.value} x ${document.querySelector('.page-title').textContent} ajouté(s) au devis`);
            
            const headerCount = document.querySelector('.header-cart-count');
            if (headerCount) headerCount.textContent = data.cart_count;
            
            setTimeout(() => {
                window.location.href = '<?= base_url('client/devis/nouveau') ?>';
            }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    } catch (err) {
        showToast('Erreur de communication', 'error');
    } finally {
        btn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
    }
});

function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'circle-exclamation'}" aria-hidden="true"></i>
        <span class="toast-message">${message}</span>
        <button class="toast-close" aria-label="Fermer"><i class="fas fa-times"></i></button>
    `;
    
    toast.querySelector('.toast-close').addEventListener('click', () => toast.remove());
    container.appendChild(toast);
    
    setTimeout(() => toast.remove(), 4000);
}
</script>