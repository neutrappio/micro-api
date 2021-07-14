<?php

namespace Mapi;

use Phalcon\Mvc\Micro;
use Phalcon\DiInterface;
use Phalcon\Di\FactoryDefault;
use Mapi\Exception\PublicException;

class Application
{
    /**
     *
     */
    private DiInterface $DiFactory;

    public function __construct()
    {
    }
}
