<?php

namespace App\Controllers;

use App\Models\Db_model;
use App\Libraries\CloudinaryService;

class Produits extends BaseController
{
    // ---------------------------------------------------------
    // LISTE + FORMULAIRE D'AJOUT
    // ---------------------------------------------------------

    public function lister_prd()
    {
        $session = session();
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }

        $pseudo = $session->get('user');
        $model  = model(Db_model::class);
        $role   = $model->get_role_by_pseudo($pseudo);

        // Seul l'administrateur gère le catalogue produits
        if (! $role || $role['cpt_role'] !== 'A') {
            return redirect()->to('/devis/lister_dev');
        }

        $data['titre']    = "Gestion des produits";
        $data['produits'] = $model->get_produits();

        return view('templates/haut2', $data)
            . view('menu/menu_administrateur')
            . view('affichage_produits', $data)
            . view('templates/bas2');
    }

    // ---------------------------------------------------------
    // AJOUT
    // ---------------------------------------------------------

    public function ajouter()
    {
        $session = session();
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }

        $pseudo = $session->get('user');
        $model  = model(Db_model::class);
        $role   = $model->get_role_by_pseudo($pseudo);

        if (! $role || $role['cpt_role'] !== 'A') {
            return redirect()->to('/devis/lister_dev');
        }

        $nom       = trim((string) $this->request->getPost('prd_nom'));
        $marque    = trim((string) $this->request->getPost('prd_marque'));
        $categorie = trim((string) $this->request->getPost('prd_categorie'));
        $prix      = (float) $this->request->getPost('prd_prix');
        $stock     = (int) $this->request->getPost('prd_stock');

        if ($nom === '' || $prix <= 0) {
            return redirect()->to('/produits/lister_prd')->with('error', 'Nom et prix sont obligatoires.');
        }

        $db = \Config\Database::connect();

        $imageUrl     = null;
        $imagePublicId = null;

        // Téléversement facultatif vers Cloudinary
        $file = $this->request->getFile('prd_image');
        if ($file !== null && $file->isValid() && $file->getSize() > 0) {
            $erreur = CloudinaryService::validateImage($file->getTempName(), $file->getClientName());

            if ($erreur !== '') {
                return redirect()->to('/produits/lister_prd')->with('error', 'Image refusée : ' . $erreur);
            }

            try {
                $televersee    = CloudinaryService::upload(
                    $file->getTempName(),
                    '',
                    'produits',
                    url_title($nom, '-', true)
                );
                $imageUrl      = $televersee['url'];
                $imagePublicId = $televersee['public_id'];
            } catch (\Throwable $e) {
                log_message('error', 'Cloudinary upload failed: {msg}', ['msg' => $e->getMessage()]);

                return redirect()->to('/produits/lister_prd')
                    ->with('error', "Le produit n'a pas été enregistré : l'envoi de l'image a échoué.");
            }
        }

        $db->table('t_produit_prd')->insert([
            'prd_nom'       => $nom,
            'prd_marque'    => $marque,
            'prd_categorie' => $categorie,
            'prd_image'     => $imageUrl,
            'prd_image_id'  => $imagePublicId,
            'prd_prix'      => $prix,
            'prd_stock'     => $stock,
        ]);

        return redirect()->to('/produits/lister_prd')->with('success', 'Produit ajouté.');
    }

    // ---------------------------------------------------------
    // MODIFICATION RAPIDE (prix / stock)
    // ---------------------------------------------------------

    public function modifier($id)
    {
        $session = session();
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }

        $pseudo = $session->get('user');
        $model  = model(Db_model::class);
        $role   = $model->get_role_by_pseudo($pseudo);

        if (! $role || $role['cpt_role'] !== 'A') {
            return redirect()->to('/devis/lister_dev');
        }

        $prix  = (float) $this->request->getPost('prd_prix');
        $stock = (int) $this->request->getPost('prd_stock');

        $db = \Config\Database::connect();

        $champs = [
            'prd_prix'  => $prix,
            'prd_stock' => $stock,
        ];

        // Remplacement facultatif de l'image
        $file = $this->request->getFile('prd_image');
        if ($file !== null && $file->isValid() && $file->getSize() > 0) {
            $erreur = CloudinaryService::validateImage($file->getTempName(), $file->getClientName());

            if ($erreur !== '') {
                return redirect()->to('/produits/lister_prd')->with('error', 'Image refusée : ' . $erreur);
            }

            $actuel = $db->table('t_produit_prd')->where('prd_id', (int) $id)->get()->getRowArray();
            $ancien = $actuel['prd_image_id'] ?? null;

            try {
                // On réutilise le même public_id : Cloudinary écrase l'ancien
                // fichier au lieu d'en créer un second.
                $televersee = CloudinaryService::upload($file->getTempName(), (string) $ancien);

                $champs['prd_image']    = $televersee['url'];
                $champs['prd_image_id'] = $televersee['public_id'];
            } catch (\Throwable $e) {
                log_message('error', 'Cloudinary replace failed: {msg}', ['msg' => $e->getMessage()]);

                return redirect()->to('/produits/lister_prd')
                    ->with('error', "Le prix et le stock n'ont pas été enregistrés : l'envoi de l'image a échoué.");
            }
        }

        $db->table('t_produit_prd')
            ->where('prd_id', (int) $id)
            ->update($champs);

        return redirect()->to('/produits/lister_prd')->with('success', 'Produit mis à jour.');
    }

    // ---------------------------------------------------------
    // SUPPRESSION
    // ---------------------------------------------------------

    public function supprimer($id)
    {
        $session = session();
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }

        $pseudo = $session->get('user');
        $model  = model(Db_model::class);
        $role   = $model->get_role_by_pseudo($pseudo);

        if (! $role || $role['cpt_role'] !== 'A') {
            return redirect()->to('/devis/lister_dev');
        }

        $db = \Config\Database::connect();

        // Un produit référencé dans t_detail_det ne peut pas être supprimé
        // (contrainte de clé étrangère) : on bloque proprement avant l'erreur SQL.
        $utilise = $db->table('t_detail_det')->where('prd_id', (int) $id)->countAllResults();
        if ($utilise > 0) {
            return redirect()->to('/produits/lister_prd')
                ->with('error', "Ce produit est utilisé dans $utilise devis et ne peut pas être supprimé.");
        }

        // Libère l'espace Cloudinary associé avant de perdre la référence.
        $imageId = $db->table('t_produit_prd')->where('prd_id', (int) $id)->get()->getRowArray()['prd_image_id'] ?? null;
        CloudinaryService::destroy($imageId);

        $db->table('t_produit_prd')->where('prd_id', (int) $id)->delete();

        return redirect()->to('/produits/lister_prd')->with('success', 'Produit supprimé.');
    }
}
