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

            if ($role && $role['pfl_role'] === 'A') {
                $menu = 'menu_administrateur';
            } else {
                $menu = 'menu_membre';
            }
            
            return view('templates/haut2')
                . view("menu/$menu")
                . view('connexion/compte_accueil')
                . view('templates/bas2');
        }


    public function creer()
    {
        // L’utilisateur a validé le formulaire en cliquant sur le bouton
        if ($this->request->getMethod()=="POST")
        {
            if (! $this->validate([
                'pseudo' => 'required|max_length[255]|min_length[2]',
                'mdp' => 'required|max_length[255]|min_length[8]',
                'nom' => 'required|max_length[60]',
                'prenom' => 'required|max_length[45]',
                'adresse' => 'required|max_length[100]',
                'telephone' => 'required|max_length[20]',
            ])) 
            {
                // La validation du formulaire a échoué, retour au formulaire !
                return view('templates/haut', ['titre' => 'Créer un compte'])
                . view('compte/compte_creer')
                . view('templates/bas');
            }
        // La validation du formulaire a réussi, traitement du formulaire
        $model = model(Db_model::class);
        $recuperation = $this->validator->getValidated();
        $model->set_compte($recuperation);
        $data['le_compte']=$recuperation['pseudo'];
        $data['le_message']="Nouveau nombre de comptes : ";
         $fichier=$this->request->getFile('fichier');

        // 2. Création du profil
        $model->set_profil($recuperation);

        if(!empty($fichier)){
        // Récupération du nom du fichier téléversé
            $nom_fichier=$fichier->getName();
        // Dépôt du fichier dans le répertoire ci/public/images
        if($fichier->move("images",$nom_fichier)){
        // + Mettre ici l’appel de la fonction membre du Db_model
        // + L’affichage de la page indiquant l’ajout du compte !
        }
        }
        //Appel de la fonction créée dans le précédent tutoriel :
        $data['le_total']=$model->get_membre();
        return view('menu/menu_visiteur')
        . view('templates/haut', $data)
        . view('compte/compte_succes')
        . view('templates/bas');
        }
        // L’utilisateur veut afficher le formulaire pour créer un compte
            return view('templates/haut', ['titre' => 'Créer un compte'])
            . view('compte/compte_creer',)
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
                    if ($role && $role['pfl_role'] === 'A') {
                        $menu = 'menu_administrateur';
                    } else {
                        $menu = 'menu_membre';
                    }

                    $session->set('user', $username);

                    // Supprimer les deux lignes get_id_by_pseudo (méthode inexistante)
                    // $user = $model->get_id_by_pseudo($username);
                    // $id = $user['cpt_pseudo'];

                    $data = []; // ← initialiser $data avant de l'utiliser

                    return view('templates/haut2')
                        . view("menu/$menu")
                        . view('connexion/compte_accueil', $data)
                        . view('templates/bas2');
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
                if ($role && $role['pfl_role'] === 'A') {
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