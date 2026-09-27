<style>
    /* Reset et styles de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 40px 20px;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    animation: fadeInUp 0.6s ease-out;
}

/* En-tête */
.header {
    background: white;
    border-radius: 20px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transform: translateY(0);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.header:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
}

.header-title h1 {
    color: #333;
    font-size: 2em;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-title p {
    color: #666;
    font-size: 1.1em;
}

/* Cartes statistiques */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    animation: slideInLeft 0.5s ease-out;
}

.stat-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.stat-card i {
    font-size: 3em;
    color: #667eea;
    transition: transform 0.3s ease;
}

.stat-card:hover i {
    transform: scale(1.1) rotate(5deg);
}

.stat-card h2 {
    font-size: 2em;
    color: #333;
    margin-bottom: 5px;
}

.stat-card p {
    color: #666;
    font-size: 0.9em;
}

/* Conteneur du tableau */
.table-container {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow-x: auto;
    animation: fadeInUp 0.6s ease-out 0.2s backwards;
}

.table-title {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.5em;
    border-left: 4px solid #667eea;
    padding-left: 15px;
}

/* Tableau stylisé */
.styled-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
}

.styled-table thead tr {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.styled-table th {
    padding: 15px 20px;
    text-align: left;
    color: white;
    font-weight: 600;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.styled-table th:first-child {
    border-radius: 10px 0 0 10px;
}

.styled-table th:last-child {
    border-radius: 0 10px 10px 0;
}

.styled-table tbody tr {
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    animation: slideInRight 0.4s ease-out;
    animation-fill-mode: backwards;
}

.styled-table tbody tr:hover {
    background: #e9ecef;
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.styled-table td {
    padding: 15px 20px;
    color: #555;
    font-size: 0.9em;
    border-top: 1px solid #e0e0e0;
}

/* Badges de statut */
.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
    transition: all 0.3s ease;
}

.status-badge.active {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
}

.status-badge.inactive {
    background: linear-gradient(135deg, #f44336 0%, #da190b 100%);
    color: white;
}

.status-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Badges de rôle */
.role-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    font-weight: 600;
    transition: all 0.3s ease;
}

.role-badge.admin {
    background: linear-gradient(135deg, #FF6B6B 0%, #c92a2a 100%);
    color: white;
}

.role-badge.member {
    background: linear-gradient(135deg, #4ECDC4 0%, #44a08d 100%);
    color: white;
}

.role-badge.invite {
    background: linear-gradient(135deg, #FFE66D 0%, #f7b731 100%);
    color: #333;
}

.role-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    animation: fadeIn 0.6s ease-out;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
    font-size: 1.5em;
}

.empty-state p {
    color: #999;
    font-size: 1em;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding: 20px 10px;
    }
    
    .header {
        padding: 20px;
    }
    
    .header-title h1 {
        font-size: 1.5em;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .table-container {
        padding: 20px;
    }
    
    .styled-table thead {
        display: none;
    }
    
    .styled-table tbody tr {
        display: block;
        margin-bottom: 20px;
        border-radius: 10px;
    }
    
    .styled-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: right;
        padding: 12px 15px;
        border: none;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .styled-table td:last-child {
        border-bottom: none;
    }
    
    .styled-table td::before {
        content: attr(data-label);
        font-weight: 600;
        text-align: left;
        color: #667eea;
    }
}

/* Effet de chargement progressif pour les lignes du tableau */
.styled-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
.styled-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
.styled-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
.styled-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
.styled-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
.styled-table tbody tr:nth-child(6) { animation-delay: 0.6s; }
.styled-table tbody tr:nth-child(7) { animation-delay: 0.7s; }
.styled-table tbody tr:nth-child(8) { animation-delay: 0.8s; }
.styled-table tbody tr:nth-child(9) { animation-delay: 0.9s; }
.styled-table tbody tr:nth-child(10) { animation-delay: 1s; }

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
}
/* Version minimaliste */
.btn-action {
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    transition: opacity 0.2s;
}

.btn-enable {
    background: #28a745;
    color: white;
}

.btn-disable {
    background: #ffc107;
    color: #333;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-action:hover {
    opacity: 0.8;
}
</style>
    </style>
<body>

<div class="container">

    <!-- En-tête -->
    <div class="header">
        <div class="header-title">
            <h1>Gestion des comptes utilisateurs</h1>
            <p>Administration complète des profils et invitations</p>
        </div>
    </div>

    <!-- Cartes -->
    <div class="stats-container">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <div>
                <h2><?= $membre->total ?></h2>
                <p>Comptes au total</p>
            </div>
        </div>

        <div class="stat-card">
            <i class="fas fa-id-card"></i>
            <div>
                <h2><?= $profil_num->total_profil ?></h2>
                <p>Profils actifs</p>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">

    <?php if (!empty($logins) && is_array($logins)) : ?>

        <h2 class="table-title"><?= $titre ?></h2>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Numéro</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($logins as $pseudos) : ?>
                    <tr>
                        <td><?= htmlspecialchars($pseudos["cpt_pseudo"]) ?></td>
                        <td><?= htmlspecialchars($pseudos["cpt_nom"]) ?></td>
                        <td><?= htmlspecialchars($pseudos["cpt_prenom"]) ?></td>
                        <td><?= htmlspecialchars($pseudos["cpt_telephone"]) ?></td>
                        <td><?= htmlspecialchars($pseudos["cpt_adresse"]) ?> </td>
                        <td><?= htmlspecialchars($pseudos["cpt_email"]) ?></td>

                        <td>
                            <span class="status-badge <?= $pseudos["cpt_statut"] == 'A' ? 'active' : 'inactive' ?>">
                                <?= $pseudos["cpt_statut"] == 'A' ? 'Activé' : 'Désactivé' ?>
                            </span>
                        </td>

                        <td>
                            <span class="role-badge 
                                <?= $pseudos["cpt_role"] == 'A' ? 'admin' : ($pseudos["cpt_role"] == 'M' ? 'member' : 'invite') ?>">
                                
                                <?= $pseudos["cpt_role"] == 'A' ? 'Admin' : ($pseudos["cpt_role"] == 'M' ? 'Membre' : 'Invité') ?>
                            </span>
                        </td>
                        <td>
                            <!-- Activer / Désactiver -->
                            <a href="<?= base_url('compte/toggle/'.$pseudos["cpt_pseudo"]) ?>" 
                            class="btn-action <?= $pseudos["cpt_statut"] == 'A' ? 'btn-disable' : 'btn-enable' ?>">
                            
                                <?= $pseudos["cpt_statut"] == 'A' ? 'Désactiver' : 'Activer' ?>
                            </a>

                            <!-- Supprimer -->
                            <a href="<?= base_url('compte/delete/'.$pseudos["cpt_pseudo"]) ?>" 
                            class="btn-action btn-delete"
                            onclick="return confirm('Tu es sûr de vouloir supprimer ce compte ?');">
                            
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else : ?>

        <div class="empty-state">
            <h3>Aucun compte pour le moment</h3>
            <p>Les comptes utilisateurs apparaîtront ici une fois créés.</p>
        </div>

    <?php endif; ?>

    </div>

</div>
</body>