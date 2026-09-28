<div class="page">
    <header class="page-header">
        <nav class="breadcrumb" aria-label="Fil d'Ariane" style="display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; font-size: .875rem; color: var(--c-muted); margin-bottom: 1rem;">
            <a href="<?= base_url('compte/accueil') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Tableau de bord</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <a href="<?= base_url('client/catalogue') ?>" style="color: var(--c-muted); text-decoration: none; transition: color .15s;">Catalogue</a>
            <i class="fas fa-chevron-right" aria-hidden="true" style="font-size: .75rem;"></i>
            <span aria-current="page" style="color: var(--c-ink); font-weight: 500;"><?= esc($titre) ?></span>
        </nav>
        <h1 class="page-title"><?= esc($titre) ?></h1>
        <p class="page-subtitle">Étape 2 sur 2 — Renseignez vos coordonnées pour finaliser la demande</p>
    </header>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-error" role="alert">
            <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <div class="devis-layout" style="display: grid; grid-template-columns: 1fr; gap: var(--gap);">
        <!-- Step Indicator -->
        <div class="step-indicator" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius); margin-bottom: var(--gap);">
            <div class="step" style="display: flex; align-items: center; gap: .5rem; flex: 1;">
                <span class="step-num" style="width: 32px; height: 32px; border-radius: 50%; background: var(--c-accent); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .875rem;">1</span>
                <span class="step-label" style="font-size: .875rem; color: var(--c-muted);">Sélection produits</span>
            </div>
            <div class="step-divider" style="flex: 1; height: 2px; background: var(--c-border);"></div>
            <div class="step active" style="display: flex; align-items: center; gap: .5rem; flex: 1; justify-content: flex-end; text-align: right;">
                <span class="step-label" style="font-size: .875rem; color: var(--c-accent); font-weight: 600;">Coordonnées</span>
                <span class="step-num" style="width: 32px; height: 32px; border-radius: 50%; background: var(--c-accent); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: .875rem;">2</span>
            </div>
        </div>

        <!-- Main Form First (Mobile First) -->
        <main class="devis-main">
            <form method="post" action="<?= base_url('client/devis/creer') ?>" id="devisForm" novalidate>
                <?= csrf_field() ?>

                <!-- Client Selection -->
                <section class="card" style="margin-bottom: var(--gap);">
                    <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1.5rem; color: var(--c-ink);"><i class="fas fa-user" aria-hidden="true" style="color: var(--c-accent); margin-right: .5rem;"></i>Informations Client</h3>
                    
                    <div class="client-mode" style="display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: .35rem; cursor: pointer; padding: .5rem .75rem; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); transition: all .15s;">
                            <input type="radio" name="client_type" value="new" onchange="toggleClientFields()" style="accent-color: var(--c-accent);"> Nouveau client
                        </label>
                        <label style="display: flex; align-items: center; gap: .35rem; cursor: pointer; padding: .5rem .75rem; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); transition: all .15s;">
                            <input type="radio" name="client_type" value="existing" onchange="toggleClientFields()" style="accent-color: var(--c-accent);"> Client existant
                        </label>
                        <label style="display: flex; align-items: center; gap: .35rem; cursor: pointer; padding: .5rem .75rem; border: 1px solid var(--c-accent); border-radius: var(--radius-sm); background: var(--c-accent-light); transition: all .15s;">
                            <input type="radio" name="client_type" value="me" checked onchange="toggleClientFields()" style="accent-color: var(--c-accent);"> Mes informations
                        </label>
                    </div>

                    <!-- Existing Client Dropdown -->
                    <div id="existingClientFields" class="form-fields" style="display:none; animation: fadeIn .2s ease;">
                        <div class="field" style="margin-bottom: 1.1rem;">
                            <label for="cli_id" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Sélectionner un client</label>
                            <select id="cli_id" name="cli_id" class="form-select" style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                                <option value="">-- Choisir un client --</option>
                                <?php foreach ($clients as $cli) : ?>
                                    <option value="<?= $cli['cli_id'] ?>">
                                        <?= esc($cli['cli_nom']) ?> (<?= esc($cli['cli_telephone']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- New Client Fields -->
                    <div id="newClientFields" class="form-fields" style="display:none; animation: fadeIn .2s ease;">
                        <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.1rem;">
                            <div class="field" style="margin-bottom: 0;">
                                <label for="cli_nom" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Nom / Raison sociale *</label>
                                <input type="text" id="cli_nom" name="cli_nom" class="form-input" value="<?= esc($clientData['cli_nom'] ?? '') ?>" required maxlength="100" style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                            </div>
                            <div class="field" style="margin-bottom: 0;">
                                <label for="cli_telephone" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Téléphone *</label>
                                <input type="tel" id="cli_telephone" name="cli_telephone" class="form-input" value="<?= esc($clientData['cli_telephone'] ?? '') ?>" required maxlength="20" placeholder="+216 XX XXX XXX" style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                            </div>
                        </div>
                        <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.1rem;">
                            <div class="field" style="margin-bottom: 0;">
                                <label for="cli_email" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Email</label>
                                <input type="email" id="cli_email" name="cli_email" class="form-input" value="<?= esc($clientData['cli_email'] ?? '') ?>" maxlength="100" placeholder="contact@exemple.com" style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                            </div>
                        </div>
                        <div class="field" style="margin-bottom: 1.1rem;">
                            <label for="cli_adresse" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Adresse *</label>
                            <textarea id="cli_adresse" name="cli_adresse" class="form-input" rows="2" required maxlength="255" placeholder="Adresse complète" style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink); resize: vertical;"><?= esc($clientData['cli_adresse'] ?? '') ?></textarea>
                        </div>
                        <div class="field" style="margin-bottom: 1.1rem;">
                            <label for="cli_region" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Région / Ville</label>
                            <input type="text" id="cli_region" name="cli_region" class="form-input" value="<?= esc($clientData['cli_region'] ?? '') ?>" maxlength="100" placeholder="Ex: Tunis, Sfax, Sousse..." style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                        </div>
                    </div>

                    <!-- My Info Fields (pre-filled, read-only mostly) -->
                    <div id="meClientFields" class="form-fields" style="animation: fadeIn .2s ease;">
                        <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.1rem;">
                            <div class="field" style="margin-bottom: 0;">
                                <label for="my_nom" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Nom</label>
                                <input type="text" id="my_nom" class="form-input" value="<?= esc($clientData['cli_nom'] ?? '') ?>" readonly style="background: var(--c-surface); color: var(--c-muted); cursor: not-allowed; width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm);">
                            </div>
                            <div class="field" style="margin-bottom: 0;">
                                <label for="my_telephone" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Téléphone</label>
                                <input type="tel" id="my_telephone" class="form-input" value="<?= esc($clientData['cli_telephone'] ?? '') ?>" readonly style="background: var(--c-surface); color: var(--c-muted); cursor: not-allowed; width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm);">
                            </div>
                        </div>
                        <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.1rem;">
                            <div class="field" style="margin-bottom: 0;">
                                <label for="my_email" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Email</label>
                                <input type="email" id="my_email" class="form-input" value="<?= esc($clientData['cli_email'] ?? '') ?>" readonly style="background: var(--c-surface); color: var(--c-muted); cursor: not-allowed; width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm);">
                            </div>
                        </div>
                        <div class="field" style="margin-bottom: 1.1rem;">
                            <label for="my_adresse" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Adresse</label>
                            <textarea id="my_adresse" class="form-input" rows="2" readonly style="background: var(--c-surface); color: var(--c-muted); cursor: not-allowed; width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); resize: vertical;"><?= esc($clientData['cli_adresse'] ?? '') ?></textarea>
                        </div>
                        <input type="hidden" name="cli_nom" value="<?= esc($clientData['cli_nom'] ?? '') ?>">
                        <input type="hidden" name="cli_telephone" value="<?= esc($clientData['cli_telephone'] ?? '') ?>">
                        <input type="hidden" name="cli_email" value="<?= esc($clientData['cli_email'] ?? '') ?>">
                        <input type="hidden" name="cli_adresse" value="<?= esc($clientData['cli_adresse'] ?? '') ?>">
                        <input type="hidden" name="cli_region" value="<?= esc($clientData['cli_region'] ?? '') ?>">
                    </div>
                </section>

                <!-- Distance & Options -->
                <section class="card" style="margin-bottom: var(--gap);">
                    <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1.5rem; color: var(--c-ink);"><i class="fas fa-route" aria-hidden="true" style="color: var(--c-accent); margin-right: .5rem;"></i>Options de livraison</h3>
                    
                    <div class="field-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.1rem;">
                        <div class="field" style="margin-bottom: 0;">
                            <label for="dev_distance" style="display: block; margin-bottom: .5rem; font-weight: 500; color: var(--c-ink);">Distance (km) — depuis notre agence</label>
                            <input type="number" id="dev_distance" name="dev_distance" class="form-input" value="0" min="0" step="0.1" oninput="calculateTotals()" style="width: 100%; padding: .625rem .875rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                            <p class="rate-hint" style="display: inline-flex; align-items: center; gap: .4rem; margin-top: .5rem; padding: .3rem .65rem; font-size: .78rem; color: var(--c-muted); background: var(--c-surface); border: 1px solid var(--c-border); border-radius: 999px;">
                                <strong style="color: var(--c-accent); font-weight: 600;"><?= number_format((float) env('devis.tarifKilometre', 1.5), 2, ',', ' ') ?> €/km</strong>
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Submit -->
                <div class="card" style="padding-top: 1.5rem; border-top: 1px solid var(--c-border);">
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: flex-end;">
                        <a href="<?= base_url('client/catalogue') ?>" class="btn btn-ghost" style="padding: .875rem 1.5rem; font-size: 1rem;">
                            <i class="fas fa-times" aria-hidden="true"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-accent" id="submitBtn" style="padding: .875rem 1.5rem; font-size: 1rem;">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Envoyer la demande de devis
                        </button>
                    </div>
                </div>
            </form>
        </main>

        <!-- Cart Summary Sidebar (Desktop Only) -->
        <aside class="devis-side" style="position: sticky; top: 2rem; display: none;">
            <div class="card">
                <h3 style="font-family: 'DM Serif Display', Georgia, serif; font-size: 1.1rem; font-weight: 400; margin-bottom: 1rem; color: var(--c-ink);">Récapitulatif du devis</h3>
                
                <div class="devis-items">
                    <?php foreach ($cart as $item) : 
                        $lineTotal = $item['prd_prix'] * $item['quantity'];
                    ?>
                        <div class="devis-line" style="display: flex; align-items: center; gap: .75rem; margin-bottom: .65rem;">
                            <?php if (!empty($item['prd_image'])) : ?>
                                <img src="<?= esc($item['prd_image']) ?>" 
                                     alt="" class="devis-line-thumb" style="width: 44px; height: 44px; flex-shrink: 0; object-fit: cover; border-radius: 8px; border: 1px solid var(--c-border); background: var(--c-surface);">
                            <?php else : ?>
                                <div class="devis-line-thumb is-empty" style="width: 44px; height: 44px; flex-shrink: 0; object-fit: cover; border-radius: 8px; border: 1px dashed var(--c-border); background: var(--c-surface); display: flex; align-items: center; justify-content: center; color: #b9b8b1; font-size: .9rem;"><i class="fas fa-cube"></i></div>
                            <?php endif; ?>
                            <select name="cart_prd[<?= $item['prd_id'] ?>][quantity]" class="form-input" style="flex: 1; min-width: 0; padding: .5rem .75rem; font-size: 14px; border: 1px solid var(--c-border); border-radius: var(--radius-sm); background: var(--c-card); color: var(--c-ink);">
                                <?php for ($i = 1; $i <= 10; $i++) : ?>
                                    <option value="<?= $i ?>" <?= $i == $item['quantity'] ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <span class="devis-line-total" style="width: 96px; flex-shrink: 0; text-align: right; font-size: .85rem; font-weight: 600; font-variant-numeric: tabular-nums; color: var(--c-ink);"><?= number_format($lineTotal, 2, ',', ' ') ?> €</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="devis-total" style="background: var(--c-card); border: 1px solid var(--c-border); border-radius: var(--radius); padding: 1.25rem 1.35rem; margin-bottom: var(--gap);">
                    <div class="devis-total-row" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .875rem; color: var(--c-muted);">
                        <span>Total produits</span>
                        <span style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;"><?= number_format($totalProduits, 2, ',', ' ') ?> €</span>
                    </div>
                    <div class="devis-total-row is-sub" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .8rem; opacity: .9; color: var(--c-muted);">
                        <span>Frais de déplacement (estimés)</span>
                        <span id="fraisKmDisplay" style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;">—</span>
                    </div>
                    <div class="devis-total-sep" style="height: 1px; background: var(--c-border); margin: .6rem 0;"></div>
                    <div class="devis-total-row" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .875rem; color: var(--c-muted);">
                        <span>Total HT</span>
                        <span id="totalHtDisplay" style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;">—</span>
                    </div>
                    <div class="devis-total-row is-sub" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: .8rem; opacity: .9; color: var(--c-muted);">
                        <span>TVA (19%)</span>
                        <span id="tvaDisplay" style="color: var(--c-ink); font-weight: 500; font-variant-numeric: tabular-nums; white-space: nowrap;">—</span>
                    </div>
                    <div class="devis-total-sep" style="height: 1px; background: var(--c-border); margin: .6rem 0;"></div>
                    <div class="devis-total-row is-grand" style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; padding: .4rem 0; font-size: 1rem; color: var(--c-ink); padding-top: .2rem;">
                        <span>Total TTC</span>
                        <span id="totalTtcDisplay" style="font-size: 1.35rem; font-weight: 700; color: var(--c-accent); white-space: nowrap;">—</span>
                    </div>
                </div>

                <a href="<?= base_url('client/catalogue') ?>" class="btn btn-ghost btn-block" style="width: 100%; justify-content: center;">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Modifier la sélection
                </a>
            </div>
        </aside>

<script>
function toggleClientFields() {
    const type = document.querySelector('input[name="client_type"]:checked')?.value || 'me';
    const existing = document.getElementById('existingClientFields');
    const newFields = document.getElementById('newClientFields');
    const meFields = document.getElementById('meClientFields');
    
    [existing, newFields, meFields].forEach(el => el.style.display = 'none');
    
    if (type === 'existing') existing.style.display = 'block';
    else if (type === 'new') newFields.style.display = 'block';
    else meFields.style.display = 'block';
    
    calculateTotals();
}

// Calculate totals
const tarifKm = <?= (float) env('devis.tarifKilometre', 1.5) ?>;
const tauxTva = 0.19;
const totalProduits = <?= $totalProduits ?>;

function calculateTotals() {
    const distance = parseFloat(document.getElementById('dev_distance').value) || 0;
    const fraisKm = distance * tarifKm;
    const totalHt = totalProduits + fraisKm;
    const tva = totalHt * tauxTva;
    const totalTtc = totalHt + tva;
    
    document.getElementById('fraisKmDisplay').textContent = fraisKm.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
    document.getElementById('totalHtDisplay').textContent = totalHt.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
    document.getElementById('tvaDisplay').textContent = tva.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
    document.getElementById('totalTtcDisplay').textContent = totalTtc.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
}

// Initial calculation
calculateTotals();

// Form validation
document.getElementById('devisForm')?.addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="client_type"]:checked')?.value;
    let valid = true;
    
    if (type === 'new') {
        const nom = document.getElementById('cli_nom').value.trim();
        const tel = document.getElementById('cli_telephone').value.trim();
        const adr = document.getElementById('cli_adresse').value.trim();
        
        if (!nom || !tel || !adr) {
            showToast('Veuillez remplir tous les champs obligatoires', 'error');
            valid = false;
        }
    }
    
    if (!valid) {
        e.preventDefault();
        return false;
    }
    
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Envoi en cours...';
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

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Step Indicator */
.step-indicator {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.step {
    display: flex;
    align-items: center;
    gap: .5rem;
    flex: 1;
}
.step:last-child {
    justify-content: flex-end;
    text-align: right;
}
.step-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--c-border);
    color: var(--c-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: .875rem;
    transition: all .2s;
}
.step.active .step-num {
    background: var(--c-accent);
    color: white;
}
.step-label {
    font-size: .875rem;
    color: var(--c-muted);
    transition: color .2s;
}
.step.active .step-label {
    color: var(--c-accent);
    font-weight: 600;
}
.step-divider {
    flex: 1;
    height: 2px;
    background: var(--c-border);
    max-width: 120px;
}

/* Responsive: Show sidebar on desktop */
@media (min-width: 992px) {
    .devis-layout {
        grid-template-columns: 380px 1fr !important;
    }
    .devis-side {
        display: block !important;
    }
    .devis-main {
        min-width: 0;
    }
}

/* Form input focus states */
.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--c-accent);
    box-shadow: 0 0 0 3px var(--c-accent-light);
}

/* Radio label hover */
.client-mode label:hover {
    border-color: var(--c-accent-mid) !important;
}
.client-mode input[type="radio"]:checked + label,
.client-mode label:has(input[type="radio"]:checked) {
    border-color: var(--c-accent) !important;
    background: var(--c-accent-light) !important;
}

/* Toast styles */
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