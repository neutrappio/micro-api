<?php
declare(strict_types=1);

use Phalcon\Mvc\Micro\Collection;
use Mapi\Controllers\User\ProfileController;

$collection = new Collection();
$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/users');

$collection->get('/me', 'index');
$collection->get('/all', 'all');

return $collection;
