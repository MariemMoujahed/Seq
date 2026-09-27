<?php
namespace App\Controllers;
use App\Models\Db_model;
use CodeIgniter\Exceptions\PageNotFoundException;
class Message extends BaseController
{
    public function __construct()
    {
    //...
    }
    public function suivre($code = null)
    {
        $model = model(Db_model::class);
        if ($code == null)
        {
            echo "
            <p style='
                color:white;
                background:red;
                padding:15px;
                text-align:center;
                font-size:18px;
                border-radius:10px;
                max-width:400px;
                margin:40px auto;
                font-family:Arial, sans-serif;
            '>
                veuillez entrer un lien avec un code de 20 caractéres valide ❌
            </p>
            ";
            return view('menu/menu_visiteur')
            . view('templates/haut')
            . view('affichage_accueil.php')
            . view('templates/bas');
            
        }
        else{
            $data['titre'] = 'Suivre la demande';
            $data['suivi'] = $model->get_code($code);
            return view('menu/menu_visiteur')
            . view('templates/haut', $data)
            . view('affichage_code')
            . view('templates/bas');
            }
    }
    public function creer()
    {
        helper('form');
        $model = model(Db_model::class);

        if ($this->request->getMethod() == "POST")
        {
            if (!$this->validate([
                'email' => 'valid_email',

            ]))
            {
                return view('menu/menu_visiteur')
                    . view('templates/haut', ['titre' => 'Suivre votre demande'])
                    . view('message/message_creer', ['erreur' => 'veuillez mettre une adresse email valide'])
                    . view('templates/bas');
            }

            if (!$this->validate([
                'email'   => 'required|max_length[255]|min_length[2]',
                'objet'   => 'required|max_length[255]|min_length[2]',
                'contenu' => 'required|max_length[500]|min_length[2]',

            ]))
            {
                return view('menu/menu_visiteur')
                    . view('templates/haut', ['titre' => 'Suivre votre demande'])
                    . view('message/message_creer', ['erreur' => 'veuillez remplir tous les champs'])
                    . view('templates/bas');
            }
            $recuperation = $this->validator->getValidated();
            $code = $model->set_message($recuperation);
            $data['erreur'] = 'Suivre votre demande';
            $data['le_code'] = $code;


            return view('menu/menu_visiteur') 
                . view('templates/haut', $data)
                . view('message/message_succes',$data)
                . view('templates/bas');
        }

        return view('menu/menu_visiteur')
            . view('templates/haut', ['titre' => 'envoyer une demande'])
            . view('message/message_creer',)
            . view('templates/bas');
    }

    public function faire_suivre()
    {
        helper('form');
        $model = model(Db_model::class);

        if ($this->request->getMethod() == "POST")
        {
            if (! $this->validate([
                'code' => 'required|max_length[20]|min_length[20]'
            ]))
            {
                return view('menu/menu_visiteur')
                    . view('templates/haut', ['titre' => 'Suivre votre demande'])
                    . view('message/suivre_formulaire', ['erreur' => 'Vous devez remplir le champs avec un code de 20 caractères exactement .'])
                    . view('templates/bas');
            }

            $code = $this->validator->getValidated()['code'];

            $data['erreur'] = 'Suivre votre demande';
            $data['titre'] = 'Suivre votre demande';
            $data['suivi'] = $model->get_code($code);

            return view('menu/menu_visiteur')
                . view('templates/haut', $data)
                . view('affichage_code')
                . view('templates/bas');
        }

        return view('menu/menu_visiteur')
            . view('templates/haut', ['titre' => 'Suivre votre demande'])
            . view('message/suivre_formulaire')
            . view('templates/bas');
    }

    public function afficher()
    {
        $session = session();
    
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }
        $pseudo = $session->get('user');
        $model = model(Db_model::class);
    
        $data['titre'] = 'les demandes';
        $data['news'] = $model->get_all_msg();
        $role = $model->get_role_by_pseudo($pseudo);
        $role = $model->get_role_by_pseudo($pseudo);
        if ($role && $role['cpt_role'] === 'A') {
            $menu = 'menu_administrateur';
        } else {
            $menu = 'menu_membre';
        }
    
        return  view('templates/haut2', $data)
            . view('menu/' . $menu)
            . view('affichage_msg')
            . view('templates/bas2');
    }

    public function repondre($msg_id)
    {
        $session = session();

        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }

        $pseudo = $session->get('user');

        $model = model(\App\Models\Db_model::class);

        $user = $model->get_id_by_pseudo($pseudo);

        $cpt_pseudo = $user['cpt_pseudo'];

        $response = $this->request->getPost('msg_response');

        $model->update_message($msg_id, $response, $cpt_pseudo);

        return redirect()->to('/message/afficher');
    }


    



}
