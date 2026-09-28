<?php

namespace App\Controllers;

use App\Models\Db_model;

class Catalogue extends BaseController
{
    /**
     * Public catalogue index - browse all products
     */
    public function index()
    {
        $model = model(Db_model::class);
        $categorie = $this->request->getGet('categorie');
        $search = $this->request->getGet('search');

        $produits = $model->get_produits();
        
        // Filter by category if provided
        if ($categorie) {
            $produits = array_filter($produits, fn($p) => ($p['prd_categorie'] ?? '') === $categorie);
        }
        
        // Filter by search if provided
        if ($search) {
            $search = mb_strtolower($search);
            $produits = array_filter($produits, fn($p) => 
                mb_strpos(mb_strtolower($p['prd_nom'] ?? ''), $search) !== false ||
                mb_strpos(mb_strtolower($p['prd_marque'] ?? ''), $search) !== false ||
                mb_strpos(mb_strtolower($p['prd_categorie'] ?? ''), $search) !== false
            );
        }

        // Get unique categories for filter
        $categories = array_unique(array_column($produits, 'prd_categorie'));
        $categories = array_filter($categories);

        $data = [
            'titre' => 'Notre Catalogue',
            'produits' => array_values($produits),
            'categories' => array_values($categories),
            'current_categorie' => $categorie,
            'search' => $search,
        ];

        return $this->renderPublicView('catalogue/index', $data);
    }

    /**
     * Public product detail
     */
    public function detail($id)
    {
        $model = model(Db_model::class);
        $produit = $model->get_produit_by_id($id);

        if (! $produit) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'titre' => $produit['prd_nom'],
            'produit' => $produit,
        ];

        return $this->renderPublicView('catalogue/detail', $data);
    }

    /**
     * Public category filter
     */
    public function categorie($categorie)
    {
        return $this->index(); // Reuses index with category filter
    }

    /**
     * Client catalogue (authenticated) - with quote cart functionality
     */
    public function clientIndex()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $categorie = $this->request->getGet('categorie');
        $search = $this->request->getGet('search');

        $produits = $model->get_produits();
        
        if ($categorie) {
            $produits = array_filter($produits, fn($p) => ($p['prd_categorie'] ?? '') === $categorie);
        }
        
        if ($search) {
            $search = mb_strtolower($search);
            $produits = array_filter($produits, fn($p) => 
                mb_strpos(mb_strtolower($p['prd_nom'] ?? ''), $search) !== false ||
                mb_strpos(mb_strtolower($p['prd_marque'] ?? ''), $search) !== false ||
                mb_strpos(mb_strtolower($p['prd_categorie'] ?? ''), $search) !== false
            );
        }

        $categories = array_unique(array_column($produits, 'prd_categorie'));
        $categories = array_filter($categories);

        // Get quote cart from session
        $cart = session()->get('quote_cart') ?? [];

        $data = [
            'titre' => 'Catalogue Produits',
            'produits' => array_values($produits),
            'categories' => array_values($categories),
            'current_categorie' => $categorie,
            'search' => $search,
            'cart' => $cart,
            'cart_count' => array_sum(array_column($cart, 'quantity')),
        ];

        return $this->renderAuthView('catalogue/client_index', $data);
    }

    /**
     * Client product detail with "Add to Quote" button
     */
    public function clientDetail($id)
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $produit = $model->get_produit_by_id($id);

        if (! $produit) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'titre' => $produit['prd_nom'],
            'produit' => $produit,
        ];

        return $this->renderAuthView('catalogue/client_detail', $data);
    }

    /**
     * Add product to quote cart (AJAX)
     */
    public function ajouterAuDevis()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non autorisé'])->setStatusCode(401);
        }

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Méthode non autorisée'])->setStatusCode(405);
        }

        $id = (int) $this->request->getPost('prd_id');
        $quantity = max(1, (int) $this->request->getPost('quantity'));
        $action = $this->request->getPost('action'); // 'update' for +/- buttons

        $model = model(Db_model::class);
        $produit = $model->get_produit_by_id($id);

        if (! $produit) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produit introuvable'])->setStatusCode(404);
        }

        // Check stock
        $stock = (int) ($produit['prd_stock'] ?? 0);

        $cart = session()->get('quote_cart') ?? [];

        if ($action === 'update' && isset($cart[$id])) {
            // Update quantity by delta (+1 or -1)
            $newQty = $cart[$id]['quantity'] + $quantity;
            
            if ($newQty <= 0) {
                // Remove from cart if quantity goes to 0 or below
                unset($cart[$id]);
            } else {
                if ($stock > 0 && $newQty > $stock) {
                    return $this->response->setJSON(['success' => false, 'message' => "Stock insuffisant (max {$stock})"])->setStatusCode(400);
                }
                $cart[$id]['quantity'] = $newQty;
            }
        } elseif (isset($cart[$id])) {
            // Adding more of same product
            $newQty = $cart[$id]['quantity'] + $quantity;
            if ($stock > 0 && $newQty > $stock) {
                return $this->response->setJSON(['success' => false, 'message' => "Stock insuffisant (max {$stock})"])->setStatusCode(400);
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            // New product
            if ($stock > 0 && $quantity > $stock) {
                return $this->response->setJSON(['success' => false, 'message' => "Stock insuffisant (max {$stock})"])->setStatusCode(400);
            }
            $cart[$id] = [
                'prd_id' => $produit['prd_id'],
                'prd_nom' => $produit['prd_nom'],
                'prd_prix' => $produit['prd_prix'],
                'prd_image' => $produit['prd_image'] ?? null,
                'quantity' => $quantity,
            ];
        }

        session()->set('quote_cart', $cart);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Ajouté au devis',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Remove product from quote cart
     */
    public function retirerDuDevis()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non autorisé'])->setStatusCode(401);
        }

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Méthode non autorisée'])->setStatusCode(405);
        }

        $id = (int) $this->request->getPost('prd_id');
        $cart = session()->get('quote_cart') ?? [];
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('quote_cart', $cart);
        }

        $cartCount = array_sum(array_column($cart, 'quantity'));

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Retiré du devis',
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Clear quote cart
     */
    public function viderDevis()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Non autorisé'])->setStatusCode(401);
        }

        session()->remove('quote_cart');

        return $this->response->setJSON(['success' => true, 'message' => 'Devis vidé']);
    }

    /**
     * Show quote request form (step 2: contact details)
     */
    public function nouveauDevis()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $cart = session()->get('quote_cart') ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/client/catalogue')
                ->with('error', 'Votre devis est vide. Ajoutez des produits d\'abord.');
        }

        $model = model(Db_model::class);
        $pseudo = $this->currentUser();
        $profil = $model->get_profil_by_pseudo($pseudo);
        $clients = $model->get_clients();

        // Pre-fill with user profile
        $clientData = [
            'cli_nom' => $profil['cpt_nom'] ?? '',
            'cli_telephone' => $profil['cpt_telephone'] ?? '',
            'cli_email' => $profil['cpt_email'] ?? '',
            'cli_adresse' => $profil['cpt_adresse'] ?? '',
        ];

        // Calculate totals
        $totalProduits = 0;
        foreach ($cart as $item) {
            $totalProduits += $item['prd_prix'] * $item['quantity'];
        }

        $data = [
            'titre' => 'Nouvelle Demande de Devis',
            'cart' => $cart,
            'clientData' => $clientData,
            'clients' => $clients,
            'totalProduits' => $totalProduits,
        ];

        return $this->renderAuthView('catalogue/nouveau_devis', $data);
    }

    /**
     * Create the quote request
     */
    public function creerDevis()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/client/catalogue');
        }

        $cart = session()->get('quote_cart') ?? [];
        
        if (empty($cart)) {
            return redirect()->to('/client/catalogue')
                ->with('error', 'Votre devis est vide.');
        }

        // Validate
        if (! $this->validate([
            'cli_id' => 'permit_empty|integer',
            'cli_nom' => 'required|max_length[100]',
            'cli_telephone' => 'required|max_length[20]',
            'cli_email' => 'permit_empty|valid_email|max_length[100]',
            'cli_adresse' => 'required|max_length[255]',
            'cli_region' => 'permit_empty|max_length[100]',
            'dev_distance' => 'permit_empty|numeric|greater_than_equal_to[0]',
        ])) {
            return redirect()->back()
                ->with('error', 'Veuillez corriger les erreurs du formulaire.')
                ->withInput();
        }

        $pseudo = $this->currentUser();
        $db = \Config\Database::connect();

        // Handle client
        $cli_id = $this->request->getPost('cli_id');
        $cli_nom = trim($this->request->getPost('cli_nom'));
        $cli_tel = trim($this->request->getPost('cli_telephone'));
        $cli_mail = trim($this->request->getPost('cli_email'));
        $cli_adr = trim($this->request->getPost('cli_adresse'));
        $cli_reg = trim($this->request->getPost('cli_region'));
        $distance = (float) $this->request->getPost('dev_distance');

        $nouveauClient = false;

        if (empty($cli_id)) {
            // Check for existing client
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
                $nouveauClient = true;
                $db->table('t_client_cli')->insert([
                    'cli_nom' => $cli_nom,
                    'cli_telephone' => $cli_tel,
                    'cli_email' => $cli_mail ?: null,
                    'cli_adresse' => $cli_adr,
                    'cli_region' => $cli_reg ?: null,
                ]);
                $cli_id = (int) $db->insertID();
            }
        } else {
            $cli_id = (int) $cli_id;
        }

        // Calculate totals
        $tarifKm = (float) env('devis.tarifKilometre', 1.5);
        $tauxTva = 0.19;
        
        $totalProduits = 0;
        $lignes = [];
        
        foreach ($cart as $item) {
            $prixLigne = round($item['prd_prix'] * $item['quantity'], 2);
            $totalProduits += $prixLigne;
            $lignes[] = [
                'prd_id' => $item['prd_id'],
                'det_quantite' => $item['quantity'],
                'det_prix' => $prixLigne,
            ];
        }

        $fraisKm = round($distance * $tarifKm, 2);
        $totalHt = round($totalProduits + $fraisKm, 2);
        $tva = round($totalHt * $tauxTva, 2);
        $totalTtc = round($totalHt + $tva, 2);

        $db->transStart();

        // Create devis
        $db->table('t_devis_dev')->insert([
            'cli_id' => $cli_id,
            'cpt_pseudo' => $pseudo,
            'dev_distance' => $distance,
            'dev_main_oeuvre' => 0,
            'dev_total_ht' => $totalHt,
            'dev_tva' => $tva,
            'dev_total_ttc' => $totalTtc,
            'dev_date_creation' => date('Y-m-d'),
            'dev_etat' => 'P',
        ]);
        $dev_id = (int) $db->insertID();

        // Create detail lines
        foreach ($lignes as $ligne) {
            $ligne['dev_id'] = $dev_id;
            $db->table('t_detail_det')->insert($ligne);
        }

        if (! $db->transStatus()) {
            $db->transRollback();
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du devis.')
                ->withInput();
        }

        $db->transCommit();

        // Clear cart
        session()->remove('quote_cart');

        return redirect()->to('/client/mes-devis')
            ->with('success', "Demande de devis #{$dev_id} envoyée avec succès ! Total : " . number_format($totalTtc, 2, ',', ' ') . " €");
    }

    /**
     * Client's quote list
     */
    public function mesDevis()
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $pseudo = $this->currentUser();
        $devis = $model->get_dev_by_user($pseudo);

        $data = [
            'titre' => 'Mes Devis',
            'devis' => $devis,
        ];

        return $this->renderAuthView('catalogue/mes_devis', $data);
    }

    /**
     * View quote detail
     */
    public function voirDevis($id)
    {
        $redirect = $this->requireMember();
        if ($redirect) {
            return $redirect;
        }

        $model = model(Db_model::class);
        $pseudo = $this->currentUser();
        $devis = $model->get_dev_by_user($pseudo);

        // Find the specific devis
        $monDevis = null;
        foreach ($devis as $d) {
            if ($d['dev_id'] == $id) {
                $monDevis = $d;
                break;
            }
        }

        if (! $monDevis) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get detail lines
        $db = \Config\Database::connect();
        $lignes = $db->table('t_detail_det d')
            ->select('d.*, p.prd_nom, p.prd_image')
            ->join('t_produit_prd p', 'p.prd_id = d.prd_id', 'left')
            ->where('d.dev_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'titre' => 'Devis #' . $id,
            'devis' => $monDevis,
            'lignes' => $lignes,
        ];

        return $this->renderAuthView('catalogue/voir_devis', $data);
    }
}