<div class="auth">
    <div class="auth-card auth-card--wide">

        <div class="auth-body">

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="auth-error" role="alert">
                    <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>

            <?= form_open_multipart('/compte/creer') ?>
            <?= csrf_field() ?>

            <div class="auth-grid">
                <div class="auth-field">
                    <label for="pseudo">Pseudo</label>
                    <input class="auth-input" type="text" id="pseudo" name="pseudo"
                           value="<?= set_value('pseudo') ?>"
                           placeholder="Votre pseudo"
                           autocomplete="username"
                           minlength="2" maxlength="60" required>
                    <span class="auth-field-msg"><?= validation_show_error('pseudo') ?></span>
                </div>

                <div class="auth-field">
                    <label for="prenom">Prénom</label>
                    <input class="auth-input" type="text" id="prenom" name="prenom"
                           value="<?= set_value('prenom') ?>"
                           placeholder="Votre prénom"
                           autocomplete="given-name"
                           maxlength="45" required>
                    <span class="auth-field-msg"><?= validation_show_error('prenom') ?></span>
                </div>
            </div>

            <div class="auth-grid">
                <div class="auth-field">
                    <label for="nom">Nom</label>
                    <input class="auth-input" type="text" id="nom" name="nom"
                           value="<?= set_value('nom') ?>"
                           placeholder="Votre nom"
                           autocomplete="family-name"
                           maxlength="60" required>
                    <span class="auth-field-msg"><?= validation_show_error('nom') ?></span>
                </div>

                <div class="auth-field">
                    <label for="adresse">Adresse</label>
                    <input class="auth-input" type="text" id="adresse" name="adresse"
                           value="<?= set_value('adresse') ?>"
                           placeholder="Votre adresse"
                           autocomplete="street-address"
                           maxlength="100" required>
                    <span class="auth-field-msg"><?= validation_show_error('adresse') ?></span>
                </div>
            </div>

            <div class="auth-grid">
                <div class="auth-field">
                    <label for="telephone">Téléphone</label>
                    <input class="auth-input" type="tel" id="telephone" name="telephone"
                           value="<?= set_value('telephone') ?>"
                           placeholder="06 00 00 00 00"
                           autocomplete="tel"
                           maxlength="20" required>
                    <span class="auth-field-msg"><?= validation_show_error('telephone') ?></span>
                </div>

                <div class="auth-field">
                    <label for="entreprise">Entreprise <span style="color:var(--gray-dark,#606870);text-transform:none;letter-spacing:0">(optionnel)</span></label>
                    <input class="auth-input" type="text" id="entreprise" name="entreprise"
                           value="<?= set_value('entreprise') ?>"
                           placeholder="Votre entreprise"
                           autocomplete="organization"
                           maxlength="100">
                    <span class="auth-field-msg"><?= validation_show_error('entreprise') ?></span>
                </div>
            </div>

            <div class="auth-field">
                <label for="mdp">Mot de passe</label>
                <div class="auth-input-wrap has-toggle">
                    <input class="auth-input" type="password" id="mdp" name="mdp"
                           placeholder="8 caractères minimum"
                           autocomplete="new-password"
                           minlength="8" required>
                    <button type="button" class="auth-toggle" data-toggle-password="mdp"
                            aria-label="Afficher le mot de passe">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="auth-strength" id="strength" hidden>
                    <div class="auth-strength-bar">
                        <div class="auth-strength-fill" id="strength-fill"></div>
                    </div>
                    <p class="auth-strength-text" id="strength-text"></p>
                </div>

                <p class="auth-hint">8 caractères minimum. Mélangez lettres, chiffres et symboles.</p>
                <span class="auth-field-msg"><?= validation_show_error('mdp') ?></span>
            </div>

            <button class="auth-btn" type="submit">Créer mon compte</button>

            <?= form_close() ?>

            <p class="auth-foot">
                Déjà un compte ?
                <a href="<?= base_url('index.php/compte/connecter') ?>">Se connecter</a>
            </p>

        </div>
    </div>
</div>

<script>
    // Afficher / masquer le mot de passe
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.getElementById(btn.dataset.togglePassword);
            const icon = btn.querySelector('i');
            const visible = input.type === 'text';

            input.type = visible ? 'password' : 'text';
            icon.className = visible ? 'fas fa-eye' : 'fas fa-eye-slash';
            btn.setAttribute('aria-label',
                visible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
        });
    });

    // Indicateur de robustesse du mot de passe (indicatif uniquement,
    // la validation réelle reste côté serveur).
    const mdp = document.getElementById('mdp');
    const strength = document.getElementById('strength');
    const fill = document.getElementById('strength-fill');
    const texte = document.getElementById('strength-text');

    const NIVEAUX = [
        { pct: 0, label: 'Trop faible', color: '#ff3030' },
        { pct: 25, label: 'Faible', color: '#ff8a2a' },
        { pct: 50, label: 'Moyen', color: '#f0c419' },
        { pct: 75, label: 'Bon', color: '#8fd14f' },
        { pct: 100, label: 'Excellent', color: '#2ecc71' }
    ];

    function evaluer() {
        const v = mdp.value;
        if (!v) {
            strength.hidden = true;
            return;
        }

        strength.hidden = false;

        let score = 0;
        if (v.length >= 8) score++;
        if (v.length >= 12) score++;
        if (/[a-z]/.test(v) && /[A-Z]/.test(v)) score++;
        if (/\d/.test(v)) score++;
        if (/[^\w\s]/.test(v)) score++;

        const n = NIVEAUX[Math.min(score, NIVEAUX.length - 1)];
        fill.style.width = Math.max(n.pct, 8) + '%';
        fill.style.backgroundColor = n.color;
        texte.textContent = 'Robustesse du mot de passe : ' + n.label.toLowerCase();
    }

    mdp.addEventListener('input', evaluer);
</script>
