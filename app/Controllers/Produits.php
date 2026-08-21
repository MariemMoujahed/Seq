<?php

namespace App\Controllers;

use App\Models\Db_model;

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
        if (! $role || $role['pfl_role'] !== 'A') {
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

        if (! $role || $role['pfl_role'] !== 'A') {
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
        $db->table('t_produit_prd')->insert([
            'prd_nom'       => $nom,
            'prd_marque'    => $marque,
            'prd_categorie' => $categorie,
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

        if (! $role || $role['pfl_role'] !== 'A') {
            return redirect()->to('/devis/lister_dev');
        }

        $prix  = (float) $this->request->getPost('prd_prix');
        $stock = (int) $this->request->getPost('prd_stock');

        $db = \Config\Database::connect();
        $db->table('t_produit_prd')
            ->where('prd_id', (int) $id)
            ->update([
                'prd_prix'  => $prix,
                'prd_stock' => $stock,
            ]);

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

        if (! $role || $role['pfl_role'] !== 'A') {
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

        $db->table('t_produit_prd')->where('prd_id', (int) $id)->delete();

        return redirect()->to('/produits/lister_prd')->with('success', 'Produit supprimé.');
    }
}
