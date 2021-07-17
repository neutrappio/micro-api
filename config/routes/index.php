<?php
declare(strict_types=1);

use Phalcon\Mvc\Micro\Collection;
use Mapi\Controllers\IndexController;

$collection = new Collection();
$collection->setHandler(IndexController::class, true);

// 'public' (avoid auth middleware)
$collection->get('/', 'index', 'public');

return $collection;
