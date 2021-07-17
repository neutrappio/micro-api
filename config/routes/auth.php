<?php
declare(strict_types=1);

use Phalcon\Mvc\Micro\Collection;
use Mapi\Controllers\Auth\AuthController;

$collection = new Collection();
$collection->setHandler(AuthController::class, true);


$collection->setPrefix('/auth');

// 'public' (avoid auth middleware)
$collection->get('/', 'index', 'public');
$collection->get('/session', 'session');

$collection->post('/login', 'login', 'public');
$collection->post('/register', 'register', 'public');


return $collection;
