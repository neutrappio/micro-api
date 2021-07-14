<?php

namespace Mapi\Handlers;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;
use Mapi\Exceptions\PublicException;

class NotFound implements IHandler
{
    public function register(IApplication $app) : void
    {
        $app->getApp()->notFound(function () {
            throw new PublicException("Route not found.");
        });
    }
}
