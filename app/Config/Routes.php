<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
//$routes->get('testdb', [App\Controllers\TestDB::class, 'index']);

use App\Controllers\Accueil;
use App\Controllers\Compte;
use App\Controllers\Message;
use App\Controllers\Devis;
use App\Controllers\Chat;
use App\Controllers\Produits;

$routes->get('/', [Accueil::class, 'afficher']);

// compte
$routes->get('compte/lister', [Compte::class, 'lister']);
$routes->get('compte/creer', [Compte::class, 'creer']);
$routes->post('compte/creer', [Compte::class, 'creer']);
$routes->get('compte/accueil', [Compte::class, 'accueil']);
$routes->get('compte/afficher_profil', [Compte::class, 'afficher_profil']);
$routes->get('compte/modifier_profil', [Compte::class, 'modifier_profil']);
$routes->post('compte/modifier_profil', [Compte::class, 'modifier_profil']);
$routes->get('compte/deconnecter', [Compte::class, 'deconnecter']);
$routes->get('compte/connecter', [Compte::class, 'connecter']);
$routes->post('compte/connecter', [Compte::class, 'connecter']);
$routes->get('compte/toggle/(:segment)', [Compte::class, 'toggle']);
$routes->get('compte/delete/(:segment)', [Compte::class, 'delete']);

// message (existant)
$routes->get('message/suivre', [Message::class, 'suivre']);
$routes->get('message/suivre/(:segment)', [Message::class, 'suivre']);
$routes->get('message/creer', [Message::class, 'creer']);
$routes->post('message/creer', [Message::class, 'creer']);
$routes->get('message/faire_suivre', [Message::class, 'faire_suivre']);
$routes->post('message/faire_suivre', [Message::class, 'faire_suivre']);
$routes->get('message/afficher', [Message::class, 'afficher']);
$routes->post('message/repondre/(:num)', [Message::class, 'repondre']);

// devis
$routes->get('devis/lister_dev', [Devis::class, 'lister_dev']);

$routes->post('devis/creer', [Devis::class, 'creer']);

$routes->get('devis/valider/(:num)', [Devis::class, 'valider/$1']);

$routes->post('devis/modifier_main_oeuvre/(:num)', [Devis::class, 'modifier_main_oeuvre/$1']);

$routes->get('devis/supprimer/(:num)', [Devis::class, 'supprimer/$1']);


// produits (admin uniquement)
$routes->get('produits/lister_prd', [Produits::class, 'lister_prd']);
$routes->post('produits/ajouter', [Produits::class, 'ajouter']);
$routes->post('produits/modifier/(:num)', [Produits::class, 'modifier/$1']);
$routes->get('produits/supprimer/(:num)', [Produits::class, 'supprimer/$1']);