<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;
class Compte extends BaseController
{
 public function __construct()
 {
 //...
 }

     public function lister()
    {
        $model = model(Db_model::class);
        $data['titre']="Liste de tous les profils";
        $data['logins'] = $model->get_all_profil();
        $data['membre'] = $model->get_membre();
        $data['profil_num'] = $model->get_profils_num();

        
        
        return view('menu/menu_administrateur')
        . view('templates/haut2', $data)
        . view('affichage_profil')
        . view('templates/bas2');
    }

    public function accueil()
        {
            $session = session();

            if (! $session->has('user')) {
                return redirect()->to('/connexion');
            }

            $username = $session->get('user');
            $model = model(Db_model::class);

            $role = $model->get_role_by_pseudo($username);

            $pseudo = $session->get('user');
        
            $model = model(Db_model::class);
        
            $user = $model->get_id_by_pseudo($pseudo);
            $id = $user['cpt_pseudo'];
            
        
            $role = $model->get_role_by_pseudo($pseudo);

            if ($role && $role['cpt_role'] === 'A') {
                $menu = 'menu_administrateur';
            } else {
                $menu = 'menu_membre';
            }

            // Dashboard data with error handling
            try {
                $membreResult = $model->get_membre();
                $membre = $membreResult && isset($membreResult->total) ? (int)$membreResult->total : 0;
            } catch (\Exception $e) {
                $membre = 0;
            }

            // Get recent devis (last 5)
            try {
                $allDevis = $model->get_all_dev();
                $recent_devis = array_slice($allDevis, 0, 5);

                // Count pending devis
                $devis_pending = 0;
                foreach ($allDevis as $d) {
                    $statut = strtolower(trim($d['dev_statut'] ?? ''));
                    if ($statut === 'en attente' || $statut === 'pending' || $statut === 'attente') {
                        $devis_pending++;
                    }
                }
            } catch (\Exception $e) {
                $allDevis = [];
                $recent_devis = [];
                $devis_pending = 0;
            }

            // Count produits
            try {
                $produits = $model->get_produits();
                $produits_count = count($produits);

                // Stock alerts (products with low stock - flexible column check)
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

            return view('templates/haut2')
                . view("menu/$menu")
                . view('connexion/compte_accueil', $data)
                . view('templates/bas2');
        }


    public function creer()
    {
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod() == 'POST') {
            if (! $this->validate([
                // Le pseudo est la clé primaire : le motif couvre aussi la
                // longueur. max_length[60] correspond à la colonne réelle
                // (l'ancien max_length[255] laissait passer des pseudos qui
                // faisaient échouer l'insertion).
                'pseudo'     => 'required|trim|regex_match[/^[A-Za-z0-9._-]{2,60}$/]',
                'mdp'        => 'required|min_length[8]|max_length[255]',
                'nom'        => 'required|trim|max_length[60]',
                'prenom'     => 'required|trim|max_length[45]',
                'adresse'    => 'required|trim|max_length[100]',
                'telephone'  => 'required|trim|max_length[20]',
                'email'      => 'permit_empty|trim|valid_email|max_length[100]',
                'entreprise' => 'permit_empty|trim|max_length[100]',
            ], [
                // CI4 attend un tableau imbriqué ['champ' => ['règle' => 'message']] ;
                // la forme plate 'champ.règle' est ignorée silencieusement et
                // affiche le message anglais par défaut.
                'pseudo' => [
                    'regex_match' => 'Le pseudo doit faire 2 à 60 caractères : lettres, chiffres, point, tiret ou underscore.',
                ],
                'mdp' => [
                    'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
                ],
                'email' => [
                    'valid_email' => 'L’adresse email est invalide.',
                ],
            ])) {
                // La validation a échoué : on revient au formulaire en
                // conservant la saisie, via withInput() + with('error').
                return redirect()->to('/compte/creer')
                    ->with('error', 'Le formulaire contient des erreurs. Vérifiez les champs signalés.')
                    ->withInput();
            }

            $model       = model(Db_model::class);
            $recuperation = $this->validator->getValidated();

            // Le pseudo est la clé primaire : sans ce contrôle, une
            // inscription avec un pseudo déjà pris renvoyait une erreur
            // MySQL brute (HTTP 500) au lieu d'un message lisible.
            if ($model->pseudo_existe($recuperation['pseudo'])) {
                return redirect()->to('/compte/creer')
                    ->with('error', 'Ce pseudo est déjà utilisé. Choisissez-en un autre.')
                    ->withInput();
            }

            if (! $model->creer_compte($recuperation)) {
                return redirect()->to('/compte/creer')
                    ->with('error', 'La création du compte a échoué. Réessayez.')
                    ->withInput();
            }

            $data['le_compte']  = $recuperation['pseudo'];
            $data['le_message'] = 'Nouveau nombre de comptes : ';
            $data['le_total']   = $model->get_membre();

            return view('templates/haut', $data)
                . view('compte/compte_succes')
                . view('templates/bas');
        }

        // L’utilisateur veut afficher le formulaire pour créer un compte
        return view('templates/haut', ['titre' => 'Créer un compte'])
            . view('compte/compte_creer')
            . view('templates/bas');
    }

        public function connecter()
            {
                $model = model(Db_model::class);

                if ($this->request->getMethod() !== 'POST') {
                    return view('templates/haut', ['titre' => 'Se connecter'])
                        . view('connexion/compte_connecter')
                        . view('templates/bas');
                }

            if (! $this->validate([
                'pseudo' => [
                    'label' => 'Pseudo',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Le pseudo est obligatoire.'
                    ]
                ],
                'mdp' => [
                    'label' => 'Mot de passe',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Le mot de passe est obligatoire.'
                    ]
                ]
            ])) {
                    return view('templates/haut', ['titre' => 'Se connecter'])
                        . view('connexion/compte_connecter')
                        . view('templates/bas');
                }

                $username = $this->request->getVar('pseudo');
                $password = $this->request->getVar('mdp');

                if ($model->connect_compte($username, $password)) {

                    $session = session();

                    $role = $model->get_role_by_pseudo($username);
                    if ($role && $role['cpt_role'] === 'A') {
                        $menu = 'menu_administrateur';
                    } else {
                        $menu = 'menu_membre';
                    }

                    $session->set('user', $username);

                    return redirect()->to('/compte/accueil');
                }

                return view('templates/haut', ['titre' => 'Se connecter'])
                    . view('connexion/compte_connecter', ['error' => 'Identifiant ou mot de passe incorrect'])
                    . view('templates/bas');
            }



            public function deconnecter()
            {
                $session=session();
                $session->destroy();
                return view('templates/haut', ['titre' => 'Se connecter'])
                . view('connexion/compte_connecter')
                . view('templates/bas');
            }

            public function afficher_profil()
            {
                $session = session();

                if (! $session->has('user')) {
                    return redirect()->to('/connexion');
                }

                $pseudo = $session->get('user');
                $model = model(Db_model::class);

                $role = $model->get_role_by_pseudo($pseudo);
                if ($role && $role['cpt_role'] === 'A') {
                    $menu = 'menu_administrateur';
                } else {
                    $menu = 'menu_membre';
                }

                $profil = $model->get_profil_by_pseudo($pseudo);

                if (!$profil) {
                    $data['profil'] = null;
                    $data['le_message'] = "Aucun profil trouvé pour cet utilisateur.";
                } else {
                    $data['profil'] = $profil;
                    $data['le_message'] = "Affichage des données du profil :";
                }

                return view('templates/haut2')
                    . view("menu/$menu")
                    . view('connexion/compte_profil', $data)
                    . view('templates/bas2');
            }

        public function modifier_profil()
        {
            $session = session();

            if (! $session->has('user')) {
                return redirect()->to('/connexion');
            }

            $pseudo = $session->get('user');
            $model = model(Db_model::class);

            $role = $model->get_role_by_pseudo($pseudo);
            $menu = ($role && $role['cpt_role'] === 'A') ? 'menu_administrateur' : 'menu_membre';

            // GET - afficher le formulaire
            if ($this->request->getMethod() !== 'POST') {
                $profil = $model->get_profil_by_pseudo($pseudo);
                $data = [
                    'profil' => $profil,
                    'menu' => $menu,
                ];
                return view('templates/haut2')
                    . view("menu/$menu")
                    . view('connexion/compte_profil_edit', $data)
                    . view('templates/bas2');
            }

            // POST - traiter la mise à jour
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
                $profil = $model->get_profil_by_pseudo($pseudo);
                return view('templates/haut2')
                    . view("menu/$menu")
                    . view('connexion/compte_profil_edit', [
                        'profil' => $profil,
                        'menu' => $menu,
                        'error' => 'Le formulaire contient des erreurs.',
                    ])
                    . view('templates/bas2');
            }

            $data = $this->validator->getValidated();

            // Mapper les noms de champs formulaire vers les colonnes DB
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

            // Vérifier le mot de passe actuel si changement demandé
            if (!empty($data['mdp_new'])) {
                $user = $model->get_user($pseudo);
                $salt = "OnRajouteDuSelPourAllongerleMDP123!!45678__Test";
                $currentHash = hash('sha256', $salt . $data['mdp_current']);
                
                if ($user->cpt_mdp !== $currentHash) {
                    $profil = $model->get_profil_by_pseudo($pseudo);
                    return view('templates/haut2')
                        . view("menu/$menu")
                        . view('connexion/compte_profil_edit', [
                            'profil' => $profil,
                            'menu' => $menu,
                            'error' => 'Mot de passe actuel incorrect.',
                        ])
                        . view('templates/bas2');
                }
                $dbData['cpt_mdp'] = hash('sha256', $salt . $data['mdp_new']);
            }

            if (! $model->update_profil($pseudo, $dbData)) {
                $profil = $model->get_profil_by_pseudo($pseudo);
                return view('templates/haut2')
                    . view("menu/$menu")
                    . view('connexion/compte_profil_edit', [
                        'profil' => $profil,
                        'menu' => $menu,
                        'error' => 'La mise à jour a échoué. Réessayez.',
                    ])
                    . view('templates/bas2');
            }

            return redirect()->to('/compte/afficher_profil')
                ->with('success', 'Profil mis à jour avec succès.');
        }

        public function toggle($pseudo)
        {
            $model = new \App\Models\Db_model();

            $user = $model->get_user($pseudo);

            if ($user) {
                $new_statut = ($user->cpt_statut == 'A') ? 'D' : 'A';
                $model->update_statut($pseudo, $new_statut);
            }

            return redirect()->to('/compte/lister');
        }


        public function delete($pseudo)
        {
            $model = new \App\Models\Db_model();

            $model->delete_profil($pseudo);
            $model->delete_compte($pseudo);

            return redirect()->to('/compte/lister');
        }

}