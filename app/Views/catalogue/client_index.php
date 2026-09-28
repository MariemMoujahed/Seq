<div class="page">
    <header class="page-header">
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle">Sélectionnez les produits pour votre demande de devis</p>
    </header>

    <!-- Cart Summary -->
    <?php if (!empty($cart)) : ?>
        <div class="card" style="margin-bottom: var(--gap); background: var(--c-accent-light); border-color: var(--c-accent);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div class="cart-summary-info" style="display: flex; align-items: center; gap: 1rem; color: var(--c-accent);">
                    <i class="fas fa-shopping-cart" aria-hidden="true" style="font-size: 1.5rem;"></i>
                    <span>
                        <strong><?= $cart_count ?></strong> produit<?= $cart_count > 1 ? 's' : '' ?> dans votre devis
                    </span>
                </div>
                <a href="<?= base_url('client/devis/nouveau') ?>" class="btn btn-accent" style="white-space: nowrap;">
                    <i class="fas fa-arrow-right" aria-hidden="true"></i> Continuer le devis
                </a>
            </div>
        </div>
    <?php endif; ?>

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
        <form class="search-form" method="get" action="<?= base_url('client/catalogue') ?>" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
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
                    <a href="<?= base_url('client/catalogue') ?>" class="search-clear" aria-label="Effacer la recherche" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--c-muted);">
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
            <a href="<?= base_url('client/catalogue') ?>" class="btn btn-primary" style="margin-top: 1rem;">Voir tout le catalogue</a>
        </div>
    <?php else : ?>
        <div class="products-grid" role="list" aria-label="Produits" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--gap);">
            <?php foreach ($produits as $produit) : ?>
                <?php 
                $inCart = isset($cart[$produit['prd_id']]);
                $cartQty = $inCart ? $cart[$produit['prd_id']]['quantity'] : 0;
                $stock = (int) ($produit['prd_stock'] ?? 0);
                $canAdd = $stock === 0 || $cartQty < $stock;
                ?>
                <article class="product-card<?= $inCart ? ' in-cart' : '' ?>" role="listitem" data-prd-id="<?= $produit['prd_id'] ?>" style="background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .15s, border-color .15s; <?= $inCart ? 'border-color: var(--c-accent); box-shadow: 0 0 0 1px var(--c-accent);' : '' ?>">
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
                        <?php if ($inCart) : ?>
                            <span class="in-cart-badge" aria-label="Déjà dans le devis" style="position: absolute; top: 1rem; right: 1rem; width: 28px; height: 28px; background: var(--c-accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; animation: popIn .2s ease;">
                                <i class="fas fa-check" aria-hidden="true"></i>
                            </span>
                        <?php endif; ?>
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
                            <span class="product-stock <?= $stock > 0 ? 'in-stock' : 'out-of-stock' ?>" style="display: inline-flex; align-items: center; gap: .35rem; font-size: .75rem; font-weight: 500; padding: .25rem .6rem; border-radius: 999px; <?= $stock > 0 ? 'background: var(--c-accent-light); color: var(--c-accent);' : 'background: var(--c-danger-bg); color: var(--c-danger);' ?>">
                                <i class="fas <?= $stock > 0 ? 'fa-check-circle' : 'fa-times-circle' ?>" aria-hidden="true"></i>
                                <?= $stock > 0 ? "Stock: $stock" : 'Rupture' ?>
                            </span>
                        </div>
                    </div>
                    <div class="product-actions" style="display: flex; flex-direction: column; gap: .5rem; padding: 1.25rem; border-top: 1px solid var(--c-border); background: var(--c-surface);">
                        <?php if ($inCart) : ?>
                            <div class="quantity-control" data-prd-id="<?= $produit['prd_id'] ?>" style="display: inline-flex; align-items: center; gap: .5rem; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); padding: .5rem .75rem;">
                                <button type="button" class="btn btn-ghost btn-sm qty-btn qty-decrease" 
                                        <?= $cartQty <= 1 ? 'disabled' : '' ?>
                                        aria-label="Diminuer la quantité"
                                        style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; <?= $cartQty <= 1 ? 'opacity: .5; cursor: not-allowed;' : '' ?>">
                                    <i class="fas fa-minus" aria-hidden="true" style="font-size: .875rem;"></i>
                                </button>
                                <span class="qty-value" aria-live="polite" style="min-width: 3rem; text-align: center; font-weight: 600; font-size: 1rem; font-variant-numeric: tabular-nums;"><?= $cartQty ?></span>
                                <button type="button" class="btn btn-ghost btn-sm qty-btn qty-increase" 
                                        <?= !$canAdd ? 'disabled' : '' ?>
                                        aria-label="Augmenter la quantité"
                                        style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; <?= !$canAdd ? 'opacity: .5; cursor: not-allowed;' : '' ?>">
                                    <i class="fas fa-plus" aria-hidden="true" style="font-size: .875rem;"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm btn-block remove-from-cart" 
                                    data-prd-id="<?= $produit['prd_id'] ?>"
                                    aria-label="Retirer du devis"
                                    style="justify-content: center; width: 100%;">
                                <i class="fas fa-trash" aria-hidden="true"></i> Retirer
                            </button>
                        <?php else : ?>
                            <button type="button" 
                                    class="btn btn-accent btn-block add-to-cart" 
                                    data-prd-id="<?= $produit['prd_id'] ?>"
                                    data-prd-nom="<?= esc($produit['prd_nom'], 'attr') ?>"
                                    data-prd-prix="<?= $produit['prd_prix'] ?>"
                                    data-prd-image="<?= esc($produit['prd_image'] ?? '', 'attr') ?>"
                                    data-stock="<?= $stock ?>"
                                    <?= !$canAdd ? 'disabled' : '' ?>
                                    aria-label="Ajouter au devis"
                                    style="justify-content: center; width: 100%; <?= !$canAdd ? 'opacity: .5; cursor: not-allowed;' : '' ?>">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                <?= $stock > 0 && $stock <= 5 ? "Ajouter (reste $stock)" : 'Ajouter au devis' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
@keyframes popIn {
    from { transform: scale(0); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.toast-container {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: .5rem;
}

.toast {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1rem 1.25rem;
    background: var(--c-card);
    border: 1px solid var(--c-border);
    border-radius: var(--radius);
    box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
    animation: slideIn .3s ease;
    max-width: 350px;
}

.toast.success { border-left: 4px solid var(--c-accent); }
.toast.error { border-left: 4px solid var(--c-danger); }

.toast i { font-size: 1.25rem; flex-shrink: 0; }
.toast.success i { color: var(--c-accent); }
.toast.error i { color: var(--c-danger); }

.toast-message { flex: 1; font-size: .9375rem; }

.toast-close {
    background: none;
    border: none;
    color: var(--c-muted);
    cursor: pointer;
    padding: .25rem;
    flex-shrink: 0;
}

.toast-close:hover { color: var(--c-ink); }

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>

<script>
// Toast notification system
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

// Event delegation for all cart actions
document.addEventListener('click', function(e) {
    // Add to cart
    const addBtn = e.target.closest('.add-to-cart');
    if (addBtn) {
        e.preventDefault();
        const prdId = addBtn.dataset.prdId;
        const prdNom = addBtn.dataset.prdNom;
        const stock = parseInt(addBtn.dataset.stock) || 0;
        
        const formData = new FormData();
        formData.append('prd_id', prdId);
        formData.append('quantity', 1);
        
        fetch('<?= base_url('catalogue/ajouterAuDevis') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`${prdNom} ajouté au devis`);
                updateCartUI(data.cart_count);
                updateProductCard(prdId, true, stock);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(() => showToast('Erreur de communication', 'error'));
        return;
    }
    
    // Quantity increase
    const incBtn = e.target.closest('.qty-increase');
    if (incBtn && !incBtn.disabled) {
        e.preventDefault();
        const card = incBtn.closest('.product-card');
        const prdId = card.dataset.prdId;
        updateQuantity(prdId, 1);
        return;
    }
    
    // Quantity decrease
    const decBtn = e.target.closest('.qty-decrease');
    if (decBtn && !decBtn.disabled) {
        e.preventDefault();
        const card = decBtn.closest('.product-card');
        const prdId = card.dataset.prdId;
        updateQuantity(prdId, -1);
        return;
    }
    
    // Remove from cart
    const removeBtn = e.target.closest('.remove-from-cart');
    if (removeBtn) {
        e.preventDefault();
        const prdId = removeBtn.dataset.prdId;
        removeFromCart(prdId);
        return;
    }
});

function updateQuantity(prdId, delta) {
    const formData = new FormData();
    formData.append('prd_id', prdId);
    formData.append('quantity', delta);
    formData.append('action', 'update');
    
    fetch('<?= base_url('catalogue/ajouterAuDevis') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateCartUI(data.cart_count);
            
            // Update quantity display on the card
            const card = document.querySelector(`.product-card[data-prd-id="${prdId}"]`);
            const qtyEl = card?.querySelector('.qty-value');
            if (qtyEl) {
                const currentQty = parseInt(qtyEl.textContent) || 0;
                const newQty = currentQty + delta;
                if (newQty <= 0) {
                    updateProductCard(prdId, false, 0);
                } else {
                    qtyEl.textContent = newQty;
                    // Update increase button disabled state if at max stock
                    const stock = parseInt(card.querySelector('.product-stock')?.textContent?.match(/\d+/)?.[0] || '0');
                    const incBtn = card.querySelector('.qty-increase');
                    if (incBtn && stock > 0 && newQty >= stock) {
                        incBtn.disabled = true;
                        incBtn.style.opacity = '.5';
                        incBtn.style.cursor = 'not-allowed';
                    } else if (incBtn) {
                        incBtn.disabled = false;
                        incBtn.style.opacity = '';
                        incBtn.style.cursor = '';
                    }
                    // Enable decrease button if it was disabled
                    const decBtn = card.querySelector('.qty-decrease');
                    if (decBtn && newQty > 1) {
                        decBtn.disabled = false;
                        decBtn.style.opacity = '';
                        decBtn.style.cursor = '';
                    }
                }
            }
        } else {
            showToast(data.message, 'error');
        }
    });
}

function removeFromCart(prdId) {
    const formData = new FormData();
    formData.append('prd_id', prdId);
    
    fetch('<?= base_url('catalogue/retirerDuDevis') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Produit retiré du devis');
            updateCartUI(data.cart_count);
            updateProductCard(prdId, false, 0);
        } else {
            showToast(data.message, 'error');
        }
    });
}

function updateCartUI(count) {
    const summary = document.querySelector('.card[style*="c-accent-light"]');
    
    if (count > 0) {
        // Show or create cart summary
        if (summary) {
            const countEl = summary.querySelector('strong');
            if (countEl) countEl.textContent = count;
            const labelEl = summary.querySelector('.cart-summary-info span');
            if (labelEl) {
                labelEl.innerHTML = `<strong>${count}</strong> produit${count > 1 ? 's' : ''} dans votre devis`;
            }
        } else {
            // Create cart summary dynamically
            createCartSummary(count);
        }
    } else {
        // Hide cart summary if empty
        if (summary) {
            summary.style.display = 'none';
        }
    }
    
    const headerCount = document.querySelector('.header-cart-count');
    if (headerCount) headerCount.textContent = count;
}

function createCartSummary(count) {
    const pageHeader = document.querySelector('.page-header');
    if (!pageHeader) return;
    
    const summaryDiv = document.createElement('div');
    summaryDiv.className = 'card';
    summaryDiv.style.cssText = 'margin-bottom: var(--gap); background: var(--c-accent-light); border-color: var(--c-accent); animation: slideDown .3s ease;';
    summaryDiv.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div class="cart-summary-info" style="display: flex; align-items: center; gap: 1rem; color: var(--c-accent);">
                <i class="fas fa-shopping-cart" aria-hidden="true" style="font-size: 1.5rem;"></i>
                <span>
                    <strong>${count}</strong> produit${count > 1 ? 's' : ''} dans votre devis
                </span>
            </div>
            <a href="<?= base_url('client/devis/nouveau') ?>" class="btn btn-accent" style="white-space: nowrap;">
                <i class="fas fa-arrow-right" aria-hidden="true"></i> Continuer le devis
            </a>
        </div>
    `;
    
    // Insert after page header
    pageHeader.insertAdjacentElement('afterend', summaryDiv);
    
    // Add animation styles if not present
    if (!document.getElementById('cart-summary-styles')) {
        const style = document.createElement('style');
        style.id = 'cart-summary-styles';
        style.textContent = `
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    }
}

function updateProductCard(prdId, inCart, stock) {
    const card = document.querySelector(`.product-card[data-prd-id="${prdId}"]`);
    if (!card) return;
    
    if (inCart) {
        card.classList.add('in-cart');
        card.style.borderColor = 'var(--c-accent)';
        card.style.boxShadow = '0 0 0 1px var(--c-accent)';
        
        const actions = card.querySelector('.product-actions');
        const prdNom = card.querySelector('.product-name')?.textContent || '';
        
        actions.innerHTML = `
            <div class="quantity-control" data-prd-id="${prdId}" style="display: inline-flex; align-items: center; gap: .5rem; background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); padding: .5rem .75rem;">
                <button type="button" class="btn btn-ghost btn-sm qty-btn qty-decrease" disabled aria-label="Diminuer la quantité" style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; opacity: .5; cursor: not-allowed;">
                    <i class="fas fa-minus" aria-hidden="true" style="font-size: .875rem;"></i>
                </button>
                <span class="qty-value" aria-live="polite" style="min-width: 3rem; text-align: center; font-weight: 600; font-size: 1rem; font-variant-numeric: tabular-nums;">1</span>
                <button type="button" class="btn btn-ghost btn-sm qty-btn qty-increase" ${stock > 0 && stock <= 1 ? 'disabled' : ''} aria-label="Augmenter la quantité" style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; ${stock > 0 && stock <= 1 ? 'opacity: .5; cursor: not-allowed;' : ''}">
                    <i class="fas fa-plus" aria-hidden="true" style="font-size: .875rem;"></i>
                </button>
            </div>
            <button type="button" class="btn btn-outline btn-sm btn-block remove-from-cart" data-prd-id="${prdId}" aria-label="Retirer du devis" style="justify-content: center; width: 100%;">
                <i class="fas fa-trash" aria-hidden="true"></i> Retirer
            </button>
        `;
        
        // Add in-cart badge
        const imgWrap = card.querySelector('.product-image');
        if (imgWrap && !imgWrap.querySelector('.in-cart-badge')) {
            const badge = document.createElement('span');
            badge.className = 'in-cart-badge';
            badge.setAttribute('aria-label', 'Déjà dans le devis');
            badge.style.cssText = 'position: absolute; top: 1rem; right: 1rem; width: 28px; height: 28px; background: var(--c-accent); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; animation: popIn .2s ease;';
            badge.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i>';
            imgWrap.appendChild(badge);
        }
    } else {
        card.classList.remove('in-cart');
        card.style.borderColor = '';
        card.style.boxShadow = '';
        
        const actions = card.querySelector('.product-actions');
        const stockEl = card.querySelector('.product-stock');
        const stock = stockEl ? parseInt(stockEl.textContent.match(/\d+/)?.[0] || '0') : 0;
        
        actions.innerHTML = `
            <button type="button" 
                    class="btn btn-accent btn-block add-to-cart" 
                    data-prd-id="${prdId}"
                    data-prd-nom="${card.querySelector('.product-name')?.textContent || ''}"
                    data-prd-prix="${card.querySelector('.product-price')?.textContent?.replace(/[^\d.,]/g, '').replace(',', '.') || 0}"
                    data-stock="${stock}"
                    ${stock > 0 && stock <= 0 ? 'disabled' : ''}
                    aria-label="Ajouter au devis"
                    style="justify-content: center; width: 100%; ${stock > 0 && stock <= 0 ? 'opacity: .5; cursor: not-allowed;' : ''}">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Ajouter au devis
            </button>
        `;
        
        // Remove in-cart badge
        const badge = card.querySelector('.in-cart-badge');
        if (badge) badge.remove();
    }
}
</script>