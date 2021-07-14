<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use function glob;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;
use Phalcon\Events\Manager;

class Router implements IHandler
{
    public function register(IApplication $application) : void
    {
        $app = $application->getApp();

        $eventsManager = new Manager();

        $middlewares = require $application->getBasePath() . '/config/middlewares.php';

        foreach ($middlewares as $type => $middleware) {
            $eventsManager->attach($type ?? "micro", new $middleware());
        }

        $app->setEventsManager($eventsManager);
    }
}
