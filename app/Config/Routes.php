<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

// --------------------- old route -----------------------
$routes->get('/','Home::index', ['as' => 'index']);
$routes->get('/index','Home::index', ['as' => 'index-test']);
$routes->get('/welcome','Home::welcome', ['as' => 'welcome']);
$routes->get('/profils','Home::profils', ['as' => 'profils']);

// $routes->get('/m.profils','Home::mProfils', ['as' => 'mprofils']);
$routes->match(['get', 'post'], 'm.profils', 'Home::mProfils');


$routes->get('/recepisse','Home::recepisse', ['as' => 'recepisse']);
$routes->get('/mrecepisse','Home::mRecepisse', ['as' => 'mrecepisse']);
$routes->get('/checkapp','Home::checkApp', ['as' => 'checkapp']);
$routes->get('/mcheckapp','Home::mCheckApp', ['as' => 'mcheckapp']);
$routes->post('/inscription','Home::inscription', ['as' => 'inscription']);
$routes->post('/m.inscription','Home::inscriptionMobile', ['as' => 'mInscription']);
// $routes->get('/m.inscription','Home::inscriptionMobile', ['as' => 'mInscription']);
// $routes->get('/inscription/(:num)','Home::inscriptionView', ['as' => 'inscription_view']);
$routes->add('inscription/(:num)', 'Home::inscriptionView/$1');

$routes->get('profildemande/(:num)', 'Home::profilDemande/$1', ['as' => 'profildemande']);

$routes->post('/register','Home::register', ['as' => 'register']);
$routes->post('/mregister','Home::registerMobile', ['as' => 'mregister']);
$routes->get('/recap_view','Home::recap_view', ['as' => 'recap_view']);
$routes->get('/mrecap','Home::mRecap', ['as' => 'mrecap']);
$routes->post('/getRecepisse','Home::getRecepisse', ['as' => 'getRecepisse']);
$routes->post('/mgetrecepisse', 'Home::mGetRecepisse', ['as' => 'mgetrecepisse']);
$routes->post('/getcheckapp','Home::getCheckApp', ['as' => 'getCheckApp']);
$routes->post('/mgetcheckapp','Home::mGetCheckApp', ['as' => 'mgetcheckapp']);

// $route['default_controller'] = 'welcome';
// $route['404_override'] = 'welcome/index';
// $route['recepisse'] = 'welcome/recepisse';
// $route['recap_view'] = 'welcome/recap_view';
// $route['getRecepisse'] = 'welcome/getRecepisse';
// $route['inscription'] = 'welcome/inscription';
// $route['register'] = 'welcome/register';
// $route['profils'] = 'welcome/profils';
$route['translate_uri_dashes'] = FALSE;
$route['welcome/([a-zA-Z0-9_-]+)/(:any)'] = 'welcome/index';

// --------------------- old route -----------------------






/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// $routes->get('/', 'AdminController::index');

$routes->get('/signup', 'UserSignupController::index');
$routes->match(['get', 'post'], 'SignupController/store', 'UserSignupController::store');
$routes->match(['get', 'post'], 'SigninController/loginAuth', 'UserSigninController::loginAuth');
$routes->get('/signin', 'UserSigninController::index');
$routes->get('/signout', 'UserSigninController::signout');

/* Admin Route Here */
$routes->get('/admin','AdminController::index');
$routes->post('admin_signin','AdminController::signin');
$routes->match(['get','post'],'/admin/dashboard','AdminController::dashboard');
$routes->get('admin_logout','AdminController::logout');


$routes->get('testindex','Home::testindex');
$routes->match(['get','post'],'/dashboard','Home::dashboard');
// $routes->match(['get','post'],'/dashboard','AdminController::dashboardNew');

$routes->get('postulants/export', 'AdminController::export');
$routes->get('download', 'AdminController::downloadFolder');

$routes->match(['get','post'], '/tableau', 'AdminController::tableau');

$routes->get('/postulants','Home::postulantsByProject', ['as' => 'postulants']); // test

$routes->get('/trombinoscope','AdminController::trombinoscope', ['as' => 'trombinoscope']);
$routes->get('/trombinoscope/download','AdminController::trombinoscopeDownload', ['as' => 'trombinoscope_download']);

$routes->get('/generate-pdf','AdminController::generatePdf', ['as' => 'generatePdf']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
