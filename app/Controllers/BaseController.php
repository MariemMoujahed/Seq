<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url'];

    /**
     * Constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn(): bool
    {
        return session()->has('user');
    }

    /**
     * Get current logged in user's pseudo
     */
    protected function currentUser(): ?string
    {
        return $this->isLoggedIn() ? session()->get('user') : null;
    }

    /**
     * Get current user's role
     */
    protected function currentUserRole(): ?string
    {
        if (! $this->isLoggedIn()) {
            return null;
        }
        $model = model(\App\Models\Db_model::class);
        $role = $model->get_role_by_pseudo($this->currentUser());
        return $role['cpt_role'] ?? null;
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin(): bool
    {
        return $this->currentUserRole() === 'A';
    }

    /**
     * Check if current user is member/client
     */
    protected function isMember(): bool
    {
        return $this->currentUserRole() === 'M';
    }

    /**
     * Require authentication - redirect to login if not logged in
     */
    protected function requireAuth(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->isLoggedIn()) {
            return redirect()->to('/compte/connecter')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        return null;
    }

    /**
     * Require admin role - redirect to dashboard if not admin
     */
    protected function requireAdmin(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        $authRedirect = $this->requireAuth();
        if ($authRedirect) {
            return $authRedirect;
        }
        if (! $this->isAdmin()) {
            return redirect()->to('/compte/accueil')->with('error', 'Accès réservé aux administrateurs.');
        }
        return null;
    }

    /**
     * Require member role (or admin) - redirect if not logged in
     */
    protected function requireMember(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        $authRedirect = $this->requireAuth();
        if ($authRedirect) {
            return $authRedirect;
        }
        return null;
    }

    /**
     * Get appropriate menu view for current user
     */
    protected function getMenuView(): string
    {
        return $this->isAdmin() ? 'menu/menu_administrateur' : 'menu/menu_membre';
    }

    /**
     * Render view with common layout (haut2 + menu + content + bas2)
     */
    protected function renderAuthView(string $contentView, array $data = []): string
    {
        $menu = $this->getMenuView();
        return view('templates/haut2', $data)
            . view($menu, $data)
            . view($contentView, $data)
            . view('templates/bas2');
    }

    /**
     * Render public view with public layout (haut + menu_visiteur + content + bas)
     */
    protected function renderPublicView(string $contentView, array $data = []): string
    {
        return view('templates/haut', $data)
            . view('menu/menu_visiteur', $data)
            . view($contentView, $data)
            . view('templates/bas');
    }
}