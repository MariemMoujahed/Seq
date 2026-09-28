<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Admin Filter - Redirects if not admin
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->has('user')) {
            return redirect()->to('/compte/connecter')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $model = model(\App\Models\Db_model::class);
        $role = $model->get_role_by_pseudo(session()->get('user'));
        
        if (! $role || $role['cpt_role'] !== 'A') {
            return redirect()->to('/compte/accueil')
                ->with('error', 'Accès réservé aux administrateurs.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }
}