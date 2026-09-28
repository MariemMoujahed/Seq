<div class="page">
  <header class="page-header">
    <div class="header-content">
      <div>
        <h1 class="page-title">Modifier mon profil</h1>
        <p class="page-subtitle">Mettez à jour vos informations personnelles</p>
      </div>
      <a href="<?= base_url('index.php/compte/afficher_profil') ?>" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i>
        Annuler
      </a>
    </div>
  </header>

  <section class="card" style="max-width: 720px;">
    <?php if (isset($error)): ?>
      <div class="alert alert-error" style="margin-bottom: 1.5rem;">
        <i class="fas fa-circle-exclamation"></i>
        <?= esc($error) ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success" style="margin-bottom: 1.5rem;">
        <i class="fas fa-circle-check"></i>
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>

    <?= form_open('index.php/compte/modifier_profil') ?>
    <?= csrf_field() ?>

    <div class="form-section">
      <h3 class="form-section-title"><i class="fas fa-user"></i> Informations personnelles</h3>
      
      <div class="field-grid">
        <div class="field">
          <label for="pseudo">Pseudo <span class="required">*</span></label>
          <input type="text" id="pseudo" name="pseudo" value="<?= esc($profil['cpt_pseudo']) ?>" readonly class="readonly-input">
          <p class="field-hint">Le pseudo ne peut pas être modifié.</p>
        </div>
        
        <div class="field">
          <label for="nom">Nom <span class="required">*</span></label>
          <input type="text" id="nom" name="nom" value="<?= esc(set_value('nom', $profil['cpt_nom'])) ?>" required>
          <?= validation_show_error('nom') ?>
        </div>
        
        <div class="field">
          <label for="prenom">Prénom <span class="required">*</span></label>
          <input type="text" id="prenom" name="prenom" value="<?= esc(set_value('prenom', $profil['cpt_prenom'])) ?>" required>
          <?= validation_show_error('prenom') ?>
        </div>
        
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= esc(set_value('email', $profil['cpt_email'])) ?>">
          <?= validation_show_error('email') ?>
        </div>
        
        <div class="field">
          <label for="telephone">Téléphone <span class="required">*</span></label>
          <input type="tel" id="telephone" name="telephone" value="<?= esc(set_value('telephone', $profil['cpt_telephone'])) ?>" required>
          <?= validation_show_error('telephone') ?>
        </div>
        
        <div class="field">
          <label for="adresse">Adresse <span class="required">*</span></label>
          <textarea id="adresse" name="adresse" rows="2" required><?= esc(set_value('adresse', $profil['cpt_adresse'])) ?></textarea>
          <?= validation_show_error('adresse') ?>
        </div>
        
        <div class="field">
          <label for="entreprise">Entreprise</label>
          <input type="text" id="entreprise" name="entreprise" value="<?= esc(set_value('entreprise', $profil['cpt_entreprise'])) ?>">
        </div>
      </div>
    </div>

    <div class="divider"></div>

    <div class="form-section">
      <h3 class="form-section-title"><i class="fas fa-key"></i> Changer le mot de passe <span class="optional">(optionnel)</span></h3>
      <p class="form-section-hint">Laissez vide pour conserver le mot de passe actuel.</p>
      
      <div class="field-grid">
        <div class="field">
          <label for="mdp_current">Mot de passe actuel <span class="required">*</span></label>
          <input type="password" id="mdp_current" name="mdp_current" autocomplete="current-password" placeholder="Nécessaire si nouveau mot de passe">
          <?= validation_show_error('mdp_current') ?>
        </div>
        
        <div class="field">
          <label for="mdp_new">Nouveau mot de passe <span class="required">*</span></label>
          <input type="password" id="mdp_new" name="mdp_new" autocomplete="new-password" placeholder="Min. 8 caractères">
          <?= validation_show_error('mdp_new') ?>
        </div>
        
        <div class="field">
          <label for="mdp_confirm">Confirmer le nouveau mot de passe <span class="required">*</span></label>
          <input type="password" id="mdp_confirm" name="mdp_confirm" autocomplete="new-password">
          <?= validation_show_error('mdp_confirm') ?>
        </div>
      </div>
    </div>

    <div class="divider"></div>

    <div class="form-actions">
      <a href="<?= base_url('index.php/compte/afficher_profil') ?>" class="btn btn-ghost">
        <i class="fas fa-times"></i>
        Annuler
      </a>
      <button type="submit" class="btn btn-accent">
        <i class="fas fa-save"></i>
        Enregistrer les modifications
      </button>
    </div>

    <?= form_close() ?>
  </section>
</div>

<style>
/* Profile edit form styles */
.header-content {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1.5rem;
  flex-wrap: wrap;
  margin-bottom: var(--gap);
}

.form-section {
  margin-bottom: 1.5rem;
}

.form-section-title {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  font-family: 'DM Serif Display', Georgia, serif;
  font-size: 1.1rem;
  font-weight: 400;
  color: var(--c-ink);
  margin: 0 0 .35rem;
}

.form-section-title .optional {
  font-family: inherit;
  font-size: .75rem;
  font-weight: 500;
  color: var(--c-muted);
  background: var(--c-surface);
  padding: .1rem .5rem;
  border-radius: 999px;
}

.form-section-hint {
  font-size: .85rem;
  color: var(--c-muted);
  margin: 0 0 1rem;
}

.field-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.1rem;
}

.field {
  margin-bottom: 0;
}

.field label {
  display: flex;
  align-items: center;
  gap: .35rem;
  font-size: 11px;
  font-weight: 600;
  color: var(--c-muted);
  text-transform: uppercase;
  letter-spacing: .07em;
  margin-bottom: 6px;
}

.required {
  color: var(--c-danger);
}

.readonly-input {
  background: var(--c-surface) !important;
  color: var(--c-muted) !important;
  cursor: not-allowed;
}

.field-hint {
  font-size: .75rem;
  color: var(--c-muted);
  margin: .35rem 0 0;
}

.field input[type="text"],
.field input[type="email"],
.field input[type="tel"],
.field input[type="password"],
.field textarea {
  width: 100%;
  padding: .6rem .75rem;
  font-family: inherit;
  font-size: 14px;
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: var(--radius-sm);
  color: var(--c-ink);
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}

.field input:focus,
.field textarea:focus {
  border-color: var(--c-accent-mid);
  box-shadow: 0 0 0 3px var(--c-accent-light);
}

.field input::placeholder,
.field textarea::placeholder {
  color: #a5a49e;
}

.field textarea {
  min-height: 80px;
  resize: vertical;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: .75rem;
  padding-top: 1rem;
  border-top: 1px solid var(--c-border);
  flex-wrap: wrap;
}

.form-actions .btn {
  min-width: 160px;
}

@media (max-width: 640px) {
  .header-content {
    flex-direction: column;
    align-items: stretch;
  }
  
  .header-content .btn {
    width: 100%;
    justify-content: center;
  }
  
  .field-grid {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .form-actions .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>