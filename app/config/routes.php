<?php
defined('PREVENT_DIRECT_ACCESS') or exit('No direct script access allowed');

$router->get('/', 'Welcome::index');
$router->match('/login', 'Auth::login', ['GET', 'POST']);
$router->match('/register', 'Auth::register', ['GET', 'POST']);
$router->get('/verify-email', 'Auth::verify_email');
$router->get('/test-email', 'Auth::test_email');

// Dashboard and Organization Routes
$router->group('/org', function() use ($router) {
    // Must be logged in to access anything under /org
    $router->get('/dashboard', 'OrgController::dashboard');

    // Feature A: Member Management (Only President/Adviser can access)
    $router->get('/members', 'MemberController::index');
    $router->match('/members/edit/(:num)', 'MemberController::edit/$1', ['GET', 'POST']);

    // Feature B: Event Management (Only Executive Members and above)
    $router->get('/events', 'EventController::index');
    $router->match('/events/create', 'EventController::create', ['GET', 'POST']);

    // Feature C: Document Repository
    $router->get('/documents', 'DocumentController::index'); 
    $router->match('/documents/upload', 'DocumentController::upload', ['GET', 'POST']);
    $router->get('/documents/download/(:num)', 'DocumentController::download/$1');

    // Feature D: Announcements
    $router->get('/announcements', 'AnnouncementController::index');
    $router->match('/announcements/create', 'AnnouncementController::create', ['GET', 'POST']);

});
// Add logout route
$router->get('/logout', 'Auth::logout');