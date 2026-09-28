<?php

namespace App\Controllers;

use App\Models\Db_model;

class Devis extends BaseController
{
    // 🔧 Taux de TVA — la table t_parametre_prm n'existe plus dans security_devis,
    // donc on le fixe ici. Si tu veux qu'il redevienne modifiable en base,
    // il faudra recréer une table de paramètres (ex: t_parametre_prm).
    private const TAUX_TVA = 0.19;

    /**
     * Tarif kilométrique en TND. Surchargé par devis.tarifKilometre dans .env
     * pour éviter de modifier le code à chaque changement tarifaire.
     */
    private function tarifKilometre(): float
    {
        return (float) env('devis.tarifKilometre', 1.5);
    }

    /**
     * Liste des devis - member sees own, admin sees all
     */
    public function lister_dev()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $pseudo = $this->currentUser();
        $model  = model(Db_model::class);
        $isAdmin = $this->isAdmin();

        $data = [
            'titre'    => 'Liste des Devis',
            'clients'  => $model->get_clients(),
            'produits' => $model->get_produits(),
            'tarifKm'  => $this->tarifKilometre(),
            'tauxTva'  => self::TAUX_TVA,
        ];

        if ($isAdmin) {
            $data['dev'] = $model->get_all_dev();
            return $this->renderAuthView('affichage_dev_admin', $data);
        }

        // Member/Commercial: own devis
        $data['dev'] = $model->get_dev_by_user($pseudo);
        return $this->renderAuthView('affichage_dev', $data);
    }

    /**
     * Créer un devis - member can create
     */
    public function creer()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        if ($this->request->getMethod() === 'POST') {
            return $this->processCreateDevis();
        }

        // GET: Show the devis creation form
        $model = model(Db_model::class);
        $data = [
            'titre'    => 'Nouveau Devis',
            'clients'  => $model->get_clients(),
            'produits' => $model->get_produits(),
            'tarifKm'  => $this->tarifKilometre(),
            'tauxTva'  => self::TAUX_TVA,
        ];

        return $this->renderAuthView('devis/_formulaire', $data);
    }

    private function processCreateDevis(): \CodeIgniter\HTTP\RedirectResponse
    {
        $pseudo = $this->currentUser();

        // --- Client : existant ou nouveau ---
        $cli_id   = $this->request->getPost('cli_id');
        $cli_nom  = trim((string) $this->request->getPost('cli_nom'));
        $cli_tel  = trim((string) $this->request->getPost('cli_telephone'));
        $cli_mail = trim((string) $this->request->getPost('cli_email'));
        $cli_adr  = trim((string) $this->request->getPost('cli_adresse'));
        $cli_reg  = trim((string) $this->request->getPost('cli_region'));

        // --- Devis ---
        $distance = (float) $this->request->getPost('dev_distance');

        // --- Lignes de produits ---
        $prd_ids = (array) ($this->request->getPost('prd_id') ?? []);
        $qtes    = (array) ($this->request->getPost('det_quantite') ?? []);

        $db      = \Config\Database::connect();
        $erreurs = [];
        $lignes  = [];
        $total_produits = 0.0;
        $sans_stock     = [];

        // 1) Client
        $nouveauClient = false;

        if ($cli_id === null || $cli_id === '') {
            if ($cli_nom === '') {
                $erreurs[] = 'Sélectionnez un client ou saisissez le nom d\'un nouveau client.';
            } else {
                $nouveauClient = true;
            }
        } else {
            $existe = $db->table('t_client_cli')->where('cli_id', (int) $cli_id)->countAllResults();
            if ($existe === 0) {
                $erreurs[] = 'Le client sélectionné est introuvable.';
                $cli_id    = null;
            }
        }

        if (mb_strlen($cli_nom) > 100) {
            $erreurs[] = 'Le nom du client ne peut pas dépasser 100 caractères.';
        }
        if ($cli_mail !== '' && ! filter_var($cli_mail, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'L\'adresse email du client est invalide.';
        }

        // 2) Distance
        if ($distance < 0) {
            $erreurs[] = 'La distance ne peut pas être négative.';
        }

        // 3) Lignes de produits
        foreach ($prd_ids as $i => $prd_id) {
            if ($prd_id === null || $prd_id === '') {
                continue;
            }

            $qte = (int) ($qtes[$i] ?? 1);
            if ($qte < 1) {
                $erreurs[] = 'Toutes les quantités doivent être supérieures ou égales à 1.';
                continue;
            }

            $produit = $db->table('t_produit_prd')->where('prd_id', (int) $prd_id)->get()->getRowArray();
            if (! $produit) {
                $erreurs[] = 'Un des produits sélectionnés n\'existe plus dans le catalogue.';
                continue;
            }

            if ((int) $produit['prd_stock'] < $qte) {
                $sans_stock[] = sprintf('« %s » (stock : %d)', $produit['prd_nom'], (int) $produit['prd_stock']);
            }

            $prix_ligne      = round((float) $produit['prd_prix'] * $qte, 2);
            $total_produits += $prix_ligne;

            $lignes[] = [
                'prd_id'       => (int) $prd_id,
                'det_quantite' => $qte,
                'det_prix'     => $prix_ligne,
            ];
        }

        if ($lignes === []) {
            $erreurs[] = 'Ajoutez au moins un produit au devis.';
        }
        if ($sans_stock !== []) {
            $erreurs[] = 'Stock insuffisant pour : ' . implode(', ', $sans_stock) . '.';
        }

        if ($erreurs !== []) {
            return redirect()->to('/devis/lister_dev')
                ->with('error', implode(' ', $erreurs))
                ->withInput();
        }

        // 4) Calcul des totaux
        $main_oeuvre = 0.0;
        $frais_km    = round($distance * $this->tarifKilometre(), 2);
        $total_ht    = round($total_produits + $frais_km + $main_oeuvre, 2);
        $tva         = round($total_ht * self::TAUX_TVA, 2);
        $total_ttc   = round($total_ht + $tva, 2);

        $db->transStart();

        // 5) Client
        if ($nouveauClient) {
            $doublon = null;
            if ($cli_tel !== '') {
                $doublon = $db->table('t_client_cli')->where('cli_telephone', $cli_tel)->get()->getRowArray();
            }
            if ($doublon === null) {
                $doublon = $db->table('t_client_cli')->where('cli_nom', $cli_nom)->get()->getRowArray();
            }

            if ($doublon !== null) {
                $cli_id = (int) $doublon['cli_id'];
            } else {
                $db->table('t_client_cli')->insert([
                    'cli_nom'       => $cli_nom,
                    'cli_telephone' => $cli_tel !== '' ? $cli_tel : null,
                    'cli_email'     => $cli_mail !== '' ? $cli_mail : null,
                    'cli_adresse'   => $cli_adr  !== '' ? $cli_adr  : null,
                    'cli_region'    => $cli_reg  !== '' ? $cli_reg  : null,
                ]);
                $cli_id = (int) $db->insertID();
            }
        } else {
            $cli_id = (int) $cli_id;
        }

        // 6) Devis
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
        $dev_id = (int) $db->insertID();

        // 7) Lignes de détail
        foreach ($lignes as $ligne) {
            $ligne['dev_id'] = $dev_id;
            $db->table('t_detail_det')->insert($ligne);
        }

        if (! $db->transStatus()) {
            $db->transRollback();
            return redirect()->to('/devis/lister_dev')
                ->with('error', 'Le devis n\'a pas pu être enregistré. Réessayez.')
                ->withInput();
        }

        $db->transCommit();

        return redirect()->to('/devis/lister_dev')
            ->with('success', 'Devis #' . $dev_id . ' créé pour un total de ' . number_format($total_ttc, 2, '.', ' ') . ' TND.');
    }

    /**
     * Valider un devis - admin only
     */
    public function valider($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        $db = \Config\Database::connect();
        $db->table('t_devis_dev')
            ->where('dev_id', (int) $id)
            ->update(['dev_etat' => 'V']);

        return redirect()->to('/devis/lister_dev')
            ->with('success', 'Devis validé.');
    }

    /**
     * Modifier main d'œuvre - admin only
     */
    public function modifier_main_oeuvre($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/devis/lister_dev');
        }

        $nouvelle_main_oeuvre = (float) $this->request->getPost('dev_main_oeuvre');
        if ($nouvelle_main_oeuvre < 0) {
            return redirect()->to('/devis/lister_dev')->with('error', 'Montant invalide.');
        }

        $model = model(Db_model::class);
        $db = \Config\Database::connect();

        $devis = $db->table('t_devis_dev')->where('dev_id', (int) $id)->get()->getRowArray();
        if ($devis === null) {
            return redirect()->to('/devis/lister_dev')->with('error', 'Devis introuvable.');
        }

        $total_produits = $model->get_total_produits((int) $id);
        $frais_km  = round((float) $devis['dev_distance'] * $this->tarifKilometre(), 2);
        $total_ht  = round($total_produits + $frais_km + $nouvelle_main_oeuvre, 2);
        $tva       = round($total_ht * self::TAUX_TVA, 2);
        $total_ttc = round($total_ht + $tva, 2);

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

    /**
     * Supprimer devis - admin only
     */
    public function supprimer($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect) {
            return $redirect;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1) Supprimer d'abord les lignes de détail
        $db->table('t_detail_det')->where('dev_id', (int) $id)->delete();

        // 2) Supprimer les interventions liées
        $db->table('t_intervention_itv')->where('dev_id', (int) $id)->delete();

        // 3) Supprimer le devis
        $db->table('t_devis_dev')->where('dev_id', (int) $id)->delete();

        if (! $db->transStatus()) {
            $db->transRollback();
            return redirect()->to('/devis/lister_dev')
                ->with('error', 'Le devis n\'a pas pu être supprimé. Réessayez.');
        }

        $db->transCommit();

        return redirect()->to('/devis/lister_dev')
            ->with('success', 'Devis supprimé.');
    }
}