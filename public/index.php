<?php
require_once __DIR__ . '/../app/Core/bootstrap.php';

use App\Core\Router;

$router = new Router();

// Student-facing routes
$router->get('/', 'StudentController@home');
$router->get('/programmes', 'StudentController@programmes');
$router->get('/programme', 'StudentController@programmeDetail');
$router->get('/staff', 'StaffController@index');
$router->get('/interest', 'StudentController@showInterestForm');
$router->post('/interest', 'StudentController@submitInterest');
$router->get('/my-interests', 'StudentController@manageInterests');
$router->post('/withdraw-interest', 'StudentController@withdrawInterest');

// Public routes
$router->get('/admin-login', 'AuthController@showLoginForm');
$router->post('/admin-login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Admin routes (require admin auth)
$router->get('/admin-dashboard', 'AdminController@dashboard');
$router->get('/admin-add-programme', 'AdminController@viewAddForm');
$router->post('/admin-add-programme', 'AdminController@addProgramme');
$router->get('/admin-edit-programme', 'AdminController@viewEditForm');
$router->post('/admin-edit-programme', 'AdminController@editProgramme');
$router->post('/admin-delete-programme', 'AdminController@deleteProgramme');
$router->post('/admin-toggle-programme-publish', 'AdminController@toggleProgrammePublish');

// Module management routes
$router->get('/admin-modules', 'AdminController@modules');
$router->post('/admin-add-module', 'AdminController@addModule');
$router->post('/admin-edit-module', 'AdminController@editModule');
$router->post('/admin-delete-module', 'AdminController@deleteModule');

// Mailing list route
$router->get('/admin-mailing-list', 'AdminController@mailingList');
$router->get('/admin-mailing-list-export', 'AdminController@exportMailingList');
$router->post('/admin-delete-interest', 'AdminController@deleteInterest');
$router->post('/admin-remove-duplicate-interests', 'AdminController@removeDuplicateInterests');
$router->post('/admin-remove-invalid-interests', 'AdminController@removeInvalidInterests');

$router->dispatch();