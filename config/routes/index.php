<?php
declare(strict_types=1);

use Phalcon\Mvc\Micro\Collection;
use Mapi\Controllers\IndexController;

$collection = new Collection();
$collection->setHandler(IndexController::class, true);

$collection->get('/', 'index');

return $collection;
