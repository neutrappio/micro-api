<?php
declare(strict_types=1);

use Phalcon\Mvc\Micro\Collection;
use Mapi\Controllers\IndexController;

$collection = new Collection();
$collection->setHandler(new IndexController());

$collection->get('/', 'hellostring');
$collection->get('/array', 'helloarray');



return $collection;
