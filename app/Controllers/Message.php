<?php

namespace App\Controllers;

use App\Models\Db_model;

class Message extends BaseController
{
    /**
     * Suivre une demande (public - by code)
     */
    public function suivre($code = null)
    {
        $model = model(Db_model::class);

        if ($code === null) {
            return $this->renderPublicView('affichage_accueil', [
                'titre' => 'Suivre une demande',
                'error' => 'Veuillez entrer un lien avec un code de 20 caractères valide.'
            ]);
        }

        $data = [
            'titre' => 'Suivre la demande',
            'suivi' => $model->get_code($code),
        ];

        return $this->renderPublicView('affichage_code', $data);
    }

    /**
     * Créer une demande (public)
     */
    public function creer()
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->processCreateMessage();
        }

        return $this->renderPublicView('message/message_creer', ['titre' => 'Envoyer une demande']);
    }

    private function processCreateMessage(): string
    {
        if (! $this->validate([
            'email'   => 'required|valid_email|max_length[255]',
            'objet'   => 'required|max_length[255]|min_length[2]',
            'contenu' => 'required|max_length[500]|min_length[2]',
        ])) {
            return $this->renderPublicView('message/message_creer', [
                'titre' => 'Envoyer une demande',
                'erreur' => 'Veuillez remplir tous les champs correctement.'
            ]);
        }

        $model = model(Db_model::class);
        $data = $this->validator->getValidated();
        $code = $model->set_message($data);

        return $this->renderPublicView('message/message_succes', [
            'titre' => 'Demande envoyée',
            'le_code' => $code,
        ]);
    }

    /**
     * Formulaire pour suivre par code (public)
     */
    public function faire_suivre()
    {
        if ($this->request->getMethod() === 'POST') {
            if (! $this->validate([
                'code' => 'required|exact_length[20]',
            ])) {
                return $this->renderPublicView('message/suivre_formulaire', [
                    'titre' => 'Suivre votre demande',
                    'erreur' => 'Vous devez entrer un code de 20 caractères exactement.'
                ]);
            }

            $code = $this->request->getPost('code');
            $model = model(Db_model::class);
            $data = [
                'titre' => 'Suivre votre demande',
                'suivi' => $model->get_code($code),
            ];

            return $this->renderPublicView('affichage_code', $data);
        }

        return $this->renderPublicView('message/suivre_formulaire', ['titre' => 'Suivre votre demande']);
    }

    /**
     * Liste des messages (auth required - admin only for all messages)
     */
    public function afficher()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $pseudo = $this->currentUser();
        $model = model(Db_model::class);
        $isAdmin = $this->isAdmin();

        $data = [
            'titre' => 'Les demandes',
            'news' => $isAdmin ? $model->get_all_msg() : $model->get_dev_by_user($pseudo), // adjust if needed
        ];

        return $this->renderAuthView('affichage_msg', $data);
    }

    /**
     * Répondre à un message (admin only)
     */
    public function repondre($msg_id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/message/afficher');
        }

        $response = $this->request->getPost('msg_response');
        $model = model(Db_model::class);
        $model->update_message($msg_id, $response, $this->currentUser());

        return redirect()->to('/message/afficher')
            ->with('success', 'Réponse envoyée.');
    }
}