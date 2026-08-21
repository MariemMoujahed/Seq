<style>
    /* =========================
   PAGE CONNEXION
   ========================= */

.login-container {
    width: 100%;
    max-width: 420px;
    margin: 50px auto;
    padding: 35px;
    box-sizing: border-box;

    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
}

/* Titre principal de la page */
.login-container h2 {
    margin: 0 0 28px;
    text-align: center;
    font-size: 26px;
    color: #1f2937;
}

/* Labels */
.login-container label {
    display: block;
    margin-bottom: 8px;
    margin-top: 18px;

    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

/* Champs */
.login-container input[type="text"],
.login-container input[type="password"] {
    width: 100%;
    box-sizing: border-box;

    padding: 12px 14px;

    border: 1px solid #d1d5db;
    border-radius: 8px;

    background: #f9fafb;
    color: #1f2937;

    font-size: 15px;
    outline: none;

    transition: all 0.2s ease;
}

.login-container input[type="text"]:focus,
.login-container input[type="password"]:focus {
    background: #ffffff;
    border-color: #2563eb;

    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

/* Bouton connexion */
.login-container input[type="submit"] {
    width: 100%;

    margin-top: 28px;
    padding: 12px;

    border: none;
    border-radius: 8px;

    background: #2563eb;
    color: white;

    font-size: 15px;
    font-weight: 600;

    cursor: pointer;
    transition: all 0.2s ease;
}

.login-container input[type="submit"]:hover {
    background: #1d4ed8;
    transform: translateY(-1px);

    box-shadow: 0 5px 12px rgba(37, 99, 235, 0.25);
}

/* Erreur de connexion */
.login-error {
    margin-top: 15px;
    padding: 11px 13px;

    border-radius: 8px;

    background: #fee2e2;
    border: 1px solid #fecaca;

    color: #b91c1c;

    font-size: 14px;
    font-weight: 500;
}

/* Erreurs de validation CodeIgniter */
.login-container .text-danger,
.login-container .error {
    display: block;

    margin-top: 6px;

    color: #dc2626;
    font-size: 13px;
}

/* Responsive */
@media (max-width: 500px) {

    .login-container {
        margin: 30px 15px;
        padding: 25px 20px;
    }

    .login-container h2 {
        font-size: 23px;
    }
}
</style>
<h2><?= $titre; ?></h2>

<div class="login-container">

    <h2>Connexion</h2>

    <?= form_open('/compte/connecter'); ?>
    <?= csrf_field() ?>

    <label for="pseudo">Pseudo :</label>
    <input
        type="text"
        id="pseudo"
        name="pseudo"
        value="<?= set_value('pseudo') ?>"
    >

    <?= validation_show_error('pseudo') ?>

    <?php if (isset($error)) : ?>
        <div class="login-error">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <label for="mdp">Mot de passe :</label>
    <input
        type="password"
        id="mdp"
        name="mdp"
    >

    <?= validation_show_error('mdp') ?>

    <input
        type="submit"
        name="submit"
        value="Se connecter"
    >

    </form>