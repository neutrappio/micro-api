<?php
declare(strict_types=1);

use Phalcon\Mvc\Micro\Collection;
use Mapi\Controllers\User\ProfileController;

$routes = new Collection();
$routes->setHandler(ProfileController::class, true);

$routes->setPrefix('/user/profile');

$routes->get('/', 'index');
$routes->get('/all', 'all');

return $routes;
