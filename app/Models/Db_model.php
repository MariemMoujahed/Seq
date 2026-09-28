<?php

namespace App\Models;
use CodeIgniter\Model;
class Db_model extends Model
{
    protected $db;
    public function __construct()
    {
    $this->db = db_connect(); //charger la base de données
    // ou
    // $this->db = \Config\Database::connect();
    }

    public function get_all_compte()
    {
    $resultat = $this->db->query("SELECT * FROM t_compte_cpt;");
    return $resultat->getResultArray();
    }
     public function lister()
    {
    $model = model(Db_model::class);
    $data['titre']="Liste de tous les comptes";
    $data['logins'] = $model->get_all_compte();
    return view('templates/haut', $data)
    . view('affichage_comptes')
    . view('templates/bas');
    }

    public function get_code($code)
    {
        $requete4 = "SELECT * FROM t_message_msg LEFT JOIN t_compte_cpt USING(cpt_pseudo) WHERE msg_code='" . $code . "';";
        $resultat4 = $this->db->query($requete4);
        return $resultat4->getResultArray();
    }

    public function set_message($saisie)
    {      
        $email   = $saisie['email'];
        $objet   = $saisie['objet'];
        $contenu = $saisie['contenu'];

        $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 20);

        $sql = "INSERT INTO t_message_msg 
                (msg_email, msg_objet, msg_contenu, msg_date, msg_code, msg_response, cpt_pseudo) 
                VALUES (?, ?, ?, CURDATE(), ?, 'Demande en cours de traitement', NULL)";

        $this->db->query($sql, [$email, $objet, $contenu, $code]);

        return $code;
    }

    public function get_membre()
    {
        $query = $this->db->query("SELECT COUNT(*) as total FROM t_compte_cpt;");
        return $query->getRow();
    }

    /**
     * Crée un compte et son profil en une seule ligne.
     *
     * Les profils vivaient dans une table séparée (t_profil_pfl) reliée par
     * une clé étrangère 1-1 : deux lignes à écrire, donc deux chances
     * d'en laisser une orpheline si le second insert échouait.
     */
    public function creer_compte(array $saisie): bool
    {
        $salt = "OnRajouteDuSelPourAllongerleMDP123!!45678__Test";

        return (bool) $this->db->table('t_compte_cpt')->insert([
            'cpt_pseudo'      => $saisie['pseudo'],
            'cpt_mdp'         => hash('sha256', $salt . $saisie['mdp']),
            'cpt_statut'      => 'A',
            'cpt_nom'         => $saisie['nom'],
            'cpt_prenom'      => $saisie['prenom'],
            'cpt_adresse'     => $saisie['adresse'],
            'cpt_telephone'   => $saisie['telephone'],
            'cpt_email'       => $saisie['email'] ?? null,
            'cpt_entreprise'  => $saisie['entreprise'] ?? null,
            'cpt_role'        => 'M',
        ]);
    }

    /**
     * Le pseudo est-il déjà pris ?
     */
    public function pseudo_existe(string $pseudo): bool
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->countAllResults() > 0;
    }

    public function connect_compte($u, $p)
    {
        // Requête préparée : addslashes() n'est pas une parade fiable
        // contre l'injection SQL (dépendant de l'encodage de connexion).
        $user = $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $u)
            ->get()
            ->getRow();

        if ($user === null) {
            return false;
        }

        $stored = $user->cpt_mdp;

        $salt = "OnRajouteDuSelPourAllongerleMDP123!!45678__Test";
        $sha256 = hash('sha256', $salt . $p);
        $md5 = md5($p);

        if ($stored === $sha256) {
            return $user;
        }
        if ($stored === $md5) {

            $new_hash = $sha256;

            $this->db->table('t_compte_cpt')
                ->where('cpt_pseudo', $user->cpt_pseudo)
                ->update(['cpt_mdp' => $new_hash]);

            return $user;
        }
        return false;
    }



    public function get_profil($u)
    {
        $row = $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $u)
            ->get()
            ->getRowArray();

        return $row === null ? false : $row;
    }

    public function get_id_by_pseudo($pseudo)
    {
        return $this->db->table('t_compte_cpt')
            ->select('cpt_pseudo')
            ->where('cpt_pseudo', $pseudo)
            ->get()
            ->getRowArray();
    }


    public function get_all_profil()
    {
        return $this->db->table('t_compte_cpt')
            ->orderBy('cpt_role', 'ASC')
            ->orderBy('cpt_pseudo', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_profils_num()
    {
        return $this->db->table('t_compte_cpt')
            ->select('COUNT(*) AS total_profil')
            ->get()
            ->getRow();
    }


    public function update_message($msg_id, $response, $cpt_pseudo)
    {
        $sql = "UPDATE t_message_msg
                SET msg_response = ?, cpt_pseudo = ?
                WHERE msg_id = ?";

        return $this->db->query($sql, [
            $response,
            $cpt_pseudo,
            $msg_id
        ]);
    }

    public function get_all_msg()
    {
        $resultat_message = $this->db->query("SELECT * FROM t_message_msg LEFT JOIN t_compte_cpt USING(cpt_pseudo) ORDER BY 
        CASE 
        WHEN msg_response = 'Demande en cours de traitement' THEN 0 
        ELSE 1 
    END,
    msg_date DESC;");
        return $resultat_message->getResultArray();
    }


    public function get_parametre($cle)
    {
        return $this->db->table('t_parametre_prm')
            ->where('prm_cle', $cle)
            ->get()
            ->getRowArray();
    }

    public function get_user($pseudo)
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->get()
            ->getRow();
    }

    public function update_statut($pseudo, $statut)
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->update(['cpt_statut' => $statut]);
    }

    public function update_role($pseudo, $role)
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->update(['cpt_role' => $role]);
    }

    public function delete_profil($pseudo)
    {
        // Le profil fait partie de t_compte_cpt : rien à supprimer séparément.
        return true;
    }

    public function delete_compte($pseudo)
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->delete();
    }

    public function update_profil(string $pseudo, array $data): bool
    {
        return (bool) $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->update($data);
    }

    public function get_role_by_pseudo(string $pseudo): ?array
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->get()
            ->getRowArray();
    }
 
    // ---------------------------------------------------------
    // CLIENTS
    // ---------------------------------------------------------
 
    public function get_clients(): array
    {
        $db = \Config\Database::connect();
        return $db->table('t_client_cli')
            ->orderBy('cli_nom', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    // ---------------------------------------------------------
    // PRODUITS
    // ---------------------------------------------------------
 
    public function get_produits(): array
    {
        $db = \Config\Database::connect();
        return $db->table('t_produit_prd')
            ->orderBy('prd_categorie', 'ASC')
            ->orderBy('prd_nom', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    // ---------------------------------------------------------
    // DEVIS
    // ---------------------------------------------------------
 
    /**
     * Tous les devis, avec infos client + liste des produits agrégée
     * (vue Administrateur / Commercial)
     */
    public function get_all_dev(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('t_devis_dev dv');
        $builder->select(
            "dv.*, cl.cli_nom, cl.cli_telephone, cl.cli_email, cl.cli_region,
             GROUP_CONCAT(CONCAT(p.prd_nom, ' x', d.det_quantite) SEPARATOR ', ') AS produits"
        );
        $builder->join('t_client_cli cl', 'cl.cli_id = dv.cli_id', 'left');
        $builder->join('t_detail_det d', 'd.dev_id = dv.dev_id', 'left');
        $builder->join('t_produit_prd p', 'p.prd_id = d.prd_id', 'left');
        $builder->groupBy('dv.dev_id');
        $builder->orderBy('dv.dev_date_creation', 'DESC');
 
        return $builder->get()->getResultArray();
    }
 
    /**
     * Devis créés par un utilisateur donné (vue Commercial / Technicien)
     */
    public function get_dev_by_user(string $pseudo): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('t_devis_dev dv');
        $builder->select(
            "dv.*, cl.cli_nom, cl.cli_telephone, cl.cli_email, cl.cli_region,
             GROUP_CONCAT(CONCAT(p.prd_nom, ' x', d.det_quantite) SEPARATOR ', ') AS produits"
        );
        $builder->join('t_client_cli cl', 'cl.cli_id = dv.cli_id', 'left');
        $builder->join('t_detail_det d', 'd.dev_id = dv.dev_id', 'left');
        $builder->join('t_produit_prd p', 'p.prd_id = d.prd_id', 'left');
        $builder->where('dv.cpt_pseudo', $pseudo);
        $builder->groupBy('dv.dev_id');
        $builder->orderBy('dv.dev_date_creation', 'DESC');
 
        return $builder->get()->getResultArray();
    }
 
    /**
     * Somme des lignes de détail (produits) d'un devis, hors main d'œuvre
     */
    public function get_total_produits(int $dev_id): float
    {
        $db = \Config\Database::connect();
        $row = $db->table('t_detail_det')
            ->selectSum('det_prix')
            ->where('dev_id', $dev_id)
            ->get()
            ->getRowArray();
 
        return (float) ($row['det_prix'] ?? 0);
    }
    public function get_profil_by_pseudo($pseudo)
    {
        return $this->db->table('t_compte_cpt')
            ->where('cpt_pseudo', $pseudo)
            ->get()
            ->getRowArray();
    }


}
    