<?php

namespace App\Controllers;

use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;

class Compte extends BaseController
{
    public function __construct()
    {
        // BaseController handles initialization
    }

    /**
     * Dashboard - different view for admin vs member
     */
    public function accueil()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $pseudo = $this->currentUser();
        $model = model(Db_model::class);
        $isAdmin = $this->isAdmin();

        if ($isAdmin) {
            return $this->adminDashboard($model);
        }

        return $this->memberDashboard($model, $pseudo);
    }

    /**
     * Admin dashboard with stats
     */
    private function adminDashboard(Db_model $model): string
    {
        try {
            $membreResult = $model->get_membre();
            $membre = $membreResult && isset($membreResult->total) ? (int)$membreResult->total : 0;
        } catch (\Exception $e) {
            $membre = 0;
        }

        try {
            $allDevis = $model->get_all_dev();
            $recent_devis = array_slice($allDevis, 0, 5);
            $devis_pending = 0;
            foreach ($allDevis as $d) {
                $statut = strtolower(trim($d['dev_statut'] ?? ''));
                if (in_array($statut, ['en attente', 'pending', 'attente'])) {
                    $devis_pending++;
                }
            }
        } catch (\Exception $e) {
            $allDevis = [];
            $recent_devis = [];
            $devis_pending = 0;
        }

        try {
            $produits = $model->get_produits();
            $produits_count = count($produits);
            $stock_alerts = 0;
            foreach ($produits as $p) {
                $stock = $p['prd_stock'] ?? $p['prd_quantite'] ?? $p['stock'] ?? $p['quantity'] ?? null;
                if ($stock !== null && is_numeric($stock) && $stock < 5) {
                    $stock_alerts++;
                }
            }
        } catch (\Exception $e) {
            $produits_count = 0;
            $stock_alerts = 0;
        }

        $data = [
            'membre' => $membre,
            'devis_pending' => $devis_pending,
            'produits_count' => $produits_count,
            'stock_alerts' => $stock_alerts,
            'recent_devis' => $recent_devis,
        ];

        return $this->renderAuthView('connexion/compte_accueil', $data);
    }

    /**
     * Member/Client dashboard
     */
    private function memberDashboard(Db_model $model, string $pseudo): string
    {
        try {
            $myDevis = $model->get_dev_by_user($pseudo);
            $recent_devis = array_slice($myDevis, 0, 5);
            $devis_total = count($myDevis);
            $devis_pending = 0;
            $devis_validated = 0;
            foreach ($myDevis as $d) {
                $statut = strtolower(trim($d['dev_statut'] ?? ''));
                if (in_array($statut, ['en attente', 'pending', 'attente'])) {
                    $devis_pending++;
                } elseif (in_array($statut, ['validé', 'valide'])) {
                    $devis_validated++;
                }
            }
        } catch (\Exception $e) {
            $recent_devis = [];
            $devis_total = $devis_pending = $devis_validated = 0;
        }

        try {
            $produits = $model->get_produits();
            $produits_count = count($produits);
            $featured_produits = array_slice($produits, 0, 6);
        } catch (\Exception $e) {
            $produits_count = 0;
            $featured_produits = [];
        }

        $profil = $model->get_profil_by_pseudo($pseudo);

        $data = [
            'profil' => $profil,
            'devis_total' => $devis_total,
            'devis_pending' => $devis_pending,
            'devis_validated' => $devis_validated,
            'recent_devis' => $recent_devis,
            'produits_count' => $produits_count,
            'featured_produits' => $featured_produits,
        ];

        return $this->renderAuthView('connexion/compte_accueil_client', $data);
    }

    /**
     * Register new account (public)
     */
    public function creer()
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->processRegistration();
        }

        return $this->renderPublicView('compte/compte_creer', ['titre' => 'Créer un compte']);
    }

    private function processRegistration(): string
    {
        if (! $this->validate([
            'pseudo'     => 'required|trim|regex_match[/^[A-Za-z0-9._-]{2,60}$/]',
            'mdp'        => 'required|min_length[8]|max_length[255]',
            'nom'        => 'required|trim|max_length[60]',
            'prenom'     => 'required|trim|max_length[45]',
            'adresse'    => 'required|trim|max_length[100]',
            'telephone'  => 'required|trim|max_length[20]',
            'email'      => 'permit_empty|trim|valid_email|max_length[100]',
            'entreprise' => 'permit_empty|trim|max_length[100]',
        ], [
            'pseudo' => [
                'regex_match' => 'Le pseudo doit faire 2 à 60 caractères : lettres, chiffres, point, tiret ou underscore.',
            ],
            'mdp' => [
                'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
            ],
            'email' => [
                'valid_email' => 'L\'adresse email est invalide.',
            ],
        ])) {
            return redirect()->to('/compte/creer')
                ->with('error', 'Le formulaire contient des erreurs. Vérifiez les champs signalés.')
                ->withInput();
        }

        $model = model(Db_model::class);
        $data = $this->validator->getValidated();

        if ($model->pseudo_existe($data['pseudo'])) {
            return redirect()->to('/compte/creer')
                ->with('error', 'Ce pseudo est déjà utilisé. Choisissez-en un autre.')
                ->withInput();
        }

        if (! $model->creer_compte($data)) {
            return redirect()->to('/compte/creer')
                ->with('error', 'La création du compte a échoué. Réessayez.')
                ->withInput();
        }

        return $this->renderPublicView('compte/compte_succes', [
            'le_compte'  => $data['pseudo'],
            'le_message' => 'Nouveau nombre de comptes : ',
            'le_total'   => $model->get_membre(),
        ]);
    }

    /**
     * Login (public)
     */
    public function connecter()
    {
        $redirect = $this->request->getGet('redirect') ?? '/compte/accueil';
        
        if ($this->request->getMethod() !== 'POST') {
            return $this->renderPublicView('connexion/compte_connecter', [
                'titre' => 'Se connecter',
                'redirect' => $redirect
            ]);
        }

        if (! $this->validate([
            'pseudo' => 'required',
            'mdp'    => 'required',
        ])) {
            return $this->renderPublicView('connexion/compte_connecter', [
                'titre' => 'Se connecter',
                'redirect' => $redirect,
                'error' => 'Pseudo et mot de passe obligatoires.'
            ]);
        }

        $username = $this->request->getVar('pseudo');
        $password = $this->request->getVar('mdp');
        $model = model(Db_model::class);

        if ($model->connect_compte($username, $password)) {
            session()->set('user', $username);
            return redirect()->to($redirect);
        }

        return $this->renderPublicView('connexion/compte_connecter', [
            'titre' => 'Se connecter',
            'redirect' => $redirect,
            'error' => 'Identifiant ou mot de passe incorrect'
        ]);
    }

    /**
     * Logout
     */
    public function deconnecter()
    {
        session()->destroy();
        return redirect()->to('/compte/connecter');
    }

    /**
     * View profile (auth required)
     */
    public function afficher_profil()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $pseudo = $this->currentUser();
        $model = model(Db_model::class);
        $profil = $model->get_profil_by_pseudo($pseudo);

        if (! $profil) {
            return redirect()->to('/compte/accueil')
                ->with('error', 'Profil introuvable.');
        }

        $data = ['profil' => $profil];
        return $this->renderAuthView('connexion/compte_profil', $data);
    }

    /**
     * Edit profile (auth required)
     */
    public function modifier_profil()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $pseudo = $this->currentUser();
        $model = model(Db_model::class);
        $profil = $model->get_profil_by_pseudo($pseudo);

        if ($this->request->getMethod() !== 'POST') {
            $data = ['profil' => $profil];
            return $this->renderAuthView('connexion/compte_profil_edit', $data);
        }

        return $this->processProfileUpdate($model, $pseudo, $profil);
    }

    private function processProfileUpdate(Db_model $model, string $pseudo, array $profil): \CodeIgniter\HTTP\RedirectResponse|string
    {
        $validationRules = [
            'nom'        => 'required|trim|max_length[60]',
            'prenom'     => 'required|trim|max_length[45]',
            'adresse'    => 'required|trim|max_length[100]',
            'telephone'  => 'required|trim|max_length[20]',
            'email'      => 'permit_empty|trim|valid_email|max_length[100]',
            'entreprise' => 'permit_empty|trim|max_length[100]',
        ];

        if ($this->request->getVar('mdp_new')) {
            $validationRules['mdp_current'] = 'required';
            $validationRules['mdp_new'] = 'required|min_length[8]|max_length[255]';
            $validationRules['mdp_confirm'] = 'required|matches[mdp_new]';
        }

        if (! $this->validate($validationRules)) {
            $data = [
                'profil' => $profil,
                'error' => 'Le formulaire contient des erreurs.',
            ];
            return $this->renderAuthView('connexion/compte_profil_edit', $data);
        }

        $data = $this->validator->getValidated();

        // Map form fields to DB columns
        $dbData = [];
        $fieldMap = [
            'nom' => 'cpt_nom',
            'prenom' => 'cpt_prenom',
            'email' => 'cpt_email',
            'telephone' => 'cpt_telephone',
            'adresse' => 'cpt_adresse',
            'entreprise' => 'cpt_entreprise',
        ];
        foreach ($fieldMap as $formField => $dbField) {
            if (isset($data[$formField])) {
                $dbData[$dbField] = $data[$formField];
            }
        }

        // Verify current password if changing
        if (!empty($data['mdp_new'])) {
            $user = $model->get_user($pseudo);
            $salt = "OnRajouteDuSelPourAllongerleMDP123!!45678__Test";
            $currentHash = hash('sha256', $salt . $data['mdp_current']);

            if ($user->cpt_mdp !== $currentHash) {
                $data = [
                    'profil' => $profil,
                    'error' => 'Mot de passe actuel incorrect.',
                ];
                return $this->renderAuthView('connexion/compte_profil_edit', $data);
            }
            $dbData['cpt_mdp'] = hash('sha256', $salt . $data['mdp_new']);
        }

        if (! $model->update_profil($pseudo, $dbData)) {
            $data = [
                'profil' => $profil,
                'error' => 'La mise à jour a échoué. Réessayez.',
            ];
            return $this->renderAuthView('connexion/compte_profil_edit', $data);
        }

        return redirect()->to('/compte/afficher_profil')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Toggle user status (admin only)
     */
    public function toggle($pseudo)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $user = $model->get_user($pseudo);

        if ($user) {
            $newStatut = ($user->cpt_statut === 'A') ? 'D' : 'A';
            $model->update_statut($pseudo, $newStatut);
        }

        return redirect()->to('/compte/lister');
    }

    /**
     * Delete user (admin only)
     */
    public function delete($pseudo)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $model->delete_profil($pseudo);
        $model->delete_compte($pseudo);

        return redirect()->to('/compte/lister');
    }

    /**
     * List all users (admin only)
     */
    public function lister()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $data = [
            'titre' => 'Liste de tous les profils',
            'logins' => $model->get_all_profil(),
            'membre' => $model->get_membre(),
            'profil_num' => $model->get_profils_num(),
        ];

        return $this->renderAuthView('affichage_profil', $data);
    }
}