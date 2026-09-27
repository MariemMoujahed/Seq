<div class="auth">
    <div class="auth-card">

        <div class="auth-body">

            <?php if (isset($error)) : ?>
                <div class="auth-error" role="alert">
                    <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
                    <span><?= esc($error) ?></span>
                </div>
            <?php endif; ?>

            <?= form_open('/compte/connecter') ?>
            <?= csrf_field() ?>

            <div class="auth-field">
                <label for="pseudo">Pseudo</label>
                <input class="auth-input"
                       type="text"
                       id="pseudo"
                       name="pseudo"
                       value="<?= set_value('pseudo') ?>"
                       placeholder="Votre pseudo"
                       autocomplete="username"
                       autofocus
                       required>
                <span class="auth-field-msg"><?= validation_show_error('pseudo') ?></span>
            </div>

            <div class="auth-field">
                <label for="mdp">Mot de passe</label>
                <div class="auth-input-wrap has-toggle">
                    <input class="auth-input"
                           type="password"
                           id="mdp"
                           name="mdp"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           required>
                    <button type="button" class="auth-toggle" data-toggle-password="mdp"
                            aria-label="Afficher le mot de passe">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <span class="auth-field-msg"><?= validation_show_error('mdp') ?></span>
            </div>

            <button class="auth-btn" type="submit">Se connecter</button>

            <?= form_close() ?>

            <p class="auth-foot">
                Pas encore de compte ?
                <a href="<?= base_url('index.php/compte/creer') ?>">Créer un compte</a>
            </p>

        </div>
    </div>
</div>

<script>
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
</script>
