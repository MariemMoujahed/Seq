<?php

namespace App\Controllers;

use App\Models\Db_model;

class Devis extends BaseController
{
    // 🔧 Taux de TVA — la table t_parametre_prm n'existe plus dans security_devis,
    // donc on le fixe ici. Si tu veux qu'il redevienne modifiable en base,
    // il faudra recréer une table de paramètres (ex: t_parametre_prm).
    private const TAUX_TVA = 0.19;

    public function __construct()
    {
        //...
    }

    // ---------------------------------------------------------
    // LISTE DES DEVIS
    // ---------------------------------------------------------

    public function lister_dev()
    {
        $session = session();
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }

        $pseudo = $session->get('user');
        $model  = model(Db_model::class);
        $role   = $model->get_role_by_pseudo($pseudo);

        $data['titre']    = "Liste des Devis";
        $data['clients']  = $model->get_clients();
        $data['produits'] = $model->get_produits();

        if ($role && $role['pfl_role'] === 'A') {
            // Administrateur : voit tous les devis
            $data['dev'] = $model->get_all_dev();
            $vue_devis   = 'affichage_dev_admin';
            $menu        = 'menu_administrateur';
        } elseif ($role && $role['pfl_role'] === 'C') {
            // Commercial : voit les devis qu'il a créés
            $data['dev'] = $model->get_dev_by_user($pseudo);
            $vue_devis   = 'affichage_dev';
            $menu        = 'menu_membre';
        } else {
            // Technicien (ou autre) : voit ses propres devis / interventions
            $data['dev'] = $model->get_dev_by_user($pseudo);
            $vue_devis   = 'affichage_dev';
            $menu        = 'menu_membre';
        }

        return view('templates/haut2', $data)
            . view('menu/' . $menu)
            . view($vue_devis, $data)
            . view('templates/bas2');
    }

    // ---------------------------------------------------------
    // CRÉATION D'UN DEVIS
    // ---------------------------------------------------------

    public function creer()
    {
        $session = session();
        if (! $session->has('user')) {
            return redirect()->to('/connexion');
        }
        $pseudo = $session->get('user');

        // --- Client : existant ou nouveau ---
        $cli_id   = $this->request->getPost('cli_id');
        $cli_nom  = $this->request->getPost('cli_nom');
        $cli_tel  = $this->request->getPost('cli_telephone');
        $cli_mail = $this->request->getPost('cli_email');
        $cli_adr  = $this->request->getPost('cli_adresse');
        $cli_reg  = $this->request->getPost('cli_region');

        // --- Devis ---
        $distance    = (float) $this->request->getPost('dev_distance');
        $main_oeuvre = (float) $this->request->getPost('dev_main_oeuvre');

        // --- Lignes de produits (tableaux issus du formulaire) ---
        $prd_ids = $this->request->getPost('prd_id')       ?? [];
        $qtes    = $this->request->getPost('det_quantite') ?? [];

        $db = \Config\Database::connect();
        $db->transStart();

        // 1) Client
        if (empty($cli_id) && ! empty($cli_nom)) {
            $db->table('t_client_cli')->insert([
                'cli_nom'       => $cli_nom,
                'cli_telephone' => $cli_tel,
                'cli_email'     => $cli_mail,
                'cli_adresse'   => $cli_adr,
                'cli_region'    => $cli_reg,
            ]);
            $cli_id = $db->insertID();
        }

        // 2) Lignes de détail + calcul du total HT (produits)
        $total_produits = 0;
        $lignes = [];

        foreach ($prd_ids as $i => $prd_id) {
            if (empty($prd_id)) {
                continue;
            }
            $qte = (int) ($qtes[$i] ?? 1);
            if ($qte <= 0) {
                $qte = 1;
            }

            $produit = $db->table('t_produit_prd')->where('prd_id', $prd_id)->get()->getRowArray();
            if (! $produit) {
                continue;
            }

            $prix_ligne      = round($produit['prd_prix'] * $qte, 2);
            $total_produits += $prix_ligne;

            $lignes[] = [
                'prd_id'       => $prd_id,
                'det_quantite' => $qte,
                'det_prix'     => $prix_ligne,
            ];
        }

        // 3) Totaux
        $total_ht  = round($total_produits + $main_oeuvre, 2);
        $tva       = round($total_ht * self::TAUX_TVA, 2);
        $total_ttc = round($total_ht + $tva, 2);

        // 4) Insertion du devis
        $db->table('t_devis_dev')->insert([
            'cli_id'            => $cli_id,
            'cpt_pseudo'        => $pseudo,
            'dev_distance'      => $distance,
            'dev_main_oeuvre'   => $main_oeuvre,
            'dev_total_ht'      => $total_ht,
            'dev_tva'           => $tva,
            'dev_total_ttc'     => $total_ttc,
            'dev_date_creation' => date('Y-m-d'),
            'dev_etat'          => 'P',
        ]);
        $dev_id = $db->insertID();

        // 5) Insertion des lignes de produits
        foreach ($lignes as $ligne) {
            $ligne['dev_id'] = $dev_id;
            $db->table('t_detail_det')->insert($ligne);
        }

        $db->transComplete();

        return redirect()->to('/devis/lister_dev');
    }

    // ---------------------------------------------------------
    // VALIDATION
    // ---------------------------------------------------------

    public function valider($id)
    {
        $db = \Config\Database::connect();
        $db->table('t_devis_dev')
            ->where('dev_id', $id)
            ->update(['dev_etat' => 'V']);

        return redirect()->to('/devis/lister_dev');
    }

    // ---------------------------------------------------------
    // MODIFIER LA MAIN D'ŒUVRE (remplace l'ancien modifier_montant)
    // ---------------------------------------------------------

    public function modifier_main_oeuvre($id)
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

        $nouvelle_main_oeuvre = (float) $this->request->getPost('dev_main_oeuvre');
        if ($nouvelle_main_oeuvre < 0) {
            return redirect()->to('/devis/lister_dev')->with('error', 'Montant invalide.');
        }

        $total_produits = $model->get_total_produits((int) $id);
        $total_ht  = round($total_produits + $nouvelle_main_oeuvre, 2);
        $tva       = round($total_ht * self::TAUX_TVA, 2);
        $total_ttc = round($total_ht + $tva, 2);

        $db = \Config\Database::connect();
        $db->table('t_devis_dev')
            ->where('dev_id', (int) $id)
            ->update([
                'dev_main_oeuvre' => $nouvelle_main_oeuvre,
                'dev_total_ht'    => $total_ht,
                'dev_tva'         => $tva,
                'dev_total_ttc'   => $total_ttc,
            ]);

        return redirect()->to('/devis/lister_dev')->with('success', 'Devis mis à jour.');
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

        $db = \Config\Database::connect();
        $db->transStart();

        // 1) Supprimer d'abord les lignes de détail (produits liés)
        $db->table('t_detail_det')->where('dev_id', $id)->delete();

        // 2) Supprimer les interventions liées, s'il y en a
        $db->table('t_intervention_itv')->where('dev_id', $id)->delete();

        // 3) Supprimer le devis
        $db->table('t_devis_dev')->where('dev_id', $id)->delete();

        $db->transComplete();

        return redirect()->to('/devis/lister_dev');
    }
}