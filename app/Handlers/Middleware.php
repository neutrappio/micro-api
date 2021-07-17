<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use function glob;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;
use Phalcon\Events\Manager;

class Middleware implements IHandler
{
    public function register(IApplication $application) : void
    {
        $eventsManager = new Manager();

        $middlewares = $application->getMiddlewares();

        foreach ($middlewares as $type => $middleware) {
            $eventsManager->attach($type ?? "micro", new $middleware());
        }

        $application->getApp()->setEventsManager($eventsManager);
    }
}
