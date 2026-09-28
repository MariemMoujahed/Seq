<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

use App\Controllers\Accueil;
use App\Controllers\Compte;
use App\Controllers\Message;
use App\Controllers\Devis;
use App\Controllers\Chat;
use App\Controllers\Produits;
use App\Controllers\Catalogue;

$routes->get('/', [Accueil::class, 'afficher']);

// Public routes (no auth required)
$routes->get('compte/creer', [Compte::class, 'creer']);
$routes->post('compte/creer', [Compte::class, 'creer']);
$routes->get('compte/connecter', [Compte::class, 'connecter']);
$routes->post('compte/connecter', [Compte::class, 'connecter']);

// Public catalogue (browse without login)
$routes->get('catalogue', [Catalogue::class, 'index']);
$routes->get('catalogue/(:num)', [Catalogue::class, 'detail/$1']);
$routes->get('catalogue/categorie/(:segment)', [Catalogue::class, 'categorie/$1']);

// Protected routes (require authentication)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Member routes (both admin and member can access)
    $routes->get('compte/accueil', [Compte::class, 'accueil']);
    $routes->get('compte/afficher_profil', [Compte::class, 'afficher_profil']);
    $routes->get('compte/modifier_profil', [Compte::class, 'modifier_profil']);
    $routes->post('compte/modifier_profil', [Compte::class, 'modifier_profil']);
    $routes->get('compte/deconnecter', [Compte::class, 'deconnecter']);

    // Client catalogue & quote request (member only)
    $routes->get('client/catalogue', [Catalogue::class, 'clientIndex']);
    $routes->get('client/catalogue/(:num)', [Catalogue::class, 'clientDetail/$1']);
    $routes->get('client/devis/nouveau', [Catalogue::class, 'nouveauDevis']);
    $routes->post('client/devis/creer', [Catalogue::class, 'creerDevis']);
    $routes->get('client/mes-devis', [Catalogue::class, 'mesDevis']);
    $routes->get('client/devis/(:num)', [Catalogue::class, 'voirDevis/$1']);

    // Cart AJAX routes (member only)
    $routes->post('catalogue/ajouterAuDevis', [Catalogue::class, 'ajouterAuDevis']);
    $routes->post('catalogue/retirerDuDevis', [Catalogue::class, 'retirerDuDevis']);
    $routes->post('catalogue/viderDevis', [Catalogue::class, 'viderDevis']);

    // Message routes
    $routes->get('message/suivre', [Message::class, 'suivre']);
    $routes->get('message/suivre/(:segment)', [Message::class, 'suivre']);
    $routes->get('message/creer', [Message::class, 'creer']);
    $routes->post('message/creer', [Message::class, 'creer']);
    $routes->get('message/faire_suivre', [Message::class, 'faire_suivre']);
    $routes->post('message/faire_suivre', [Message::class, 'faire_suivre']);
    $routes->get('message/afficher', [Message::class, 'afficher']);
    $routes->post('message/repondre/(:num)', [Message::class, 'repondre']);

    // Devis routes (member can see their own, admin all)
    $routes->get('devis/lister_dev', [Devis::class, 'lister_dev']);
    $routes->get('devis/creer', [Devis::class, 'creer']); // GET for form
    $routes->post('devis/creer', [Devis::class, 'creer']);
});

// Admin only routes
$routes->group('', ['filter' => 'admin'], function ($routes) {
    // User management
    $routes->get('compte/lister', [Compte::class, 'lister']);
    $routes->get('compte/toggle/(:segment)', [Compte::class, 'toggle']);
    $routes->get('compte/delete/(:segment)', [Compte::class, 'delete']);

    // Devis admin actions
    $routes->get('devis/valider/(:num)', [Devis::class, 'valider/$1']);
    $routes->post('devis/modifier_main_oeuvre/(:num)', [Devis::class, 'modifier_main_oeuvre/$1']);
    $routes->get('devis/supprimer/(:num)', [Devis::class, 'supprimer/$1']);

    // Produits (admin only)
    $routes->get('produits/lister_prd', [Produits::class, 'lister_prd']);
    $routes->post('produits/ajouter', [Produits::class, 'ajouter']);
    $routes->post('produits/modifier/(:num)', [Produits::class, 'modifier/$1']);
    $routes->get('produits/supprimer/(:num)', [Produits::class, 'supprimer/$1']);
});