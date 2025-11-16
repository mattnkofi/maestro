<?php
defined('PREVENT_DIRECT_ACCESS') or exit('No direct script access allowed');

$router->get('/', 'Welcome::index');
$router->match('/login', 'Auth::login', ['GET', 'POST']);
$router->match('/register', 'Auth::register', ['GET', 'POST']);
$router->get('/verify-email', 'Auth::verify_email');
$router->get('/test-email', 'Auth::test_email');
