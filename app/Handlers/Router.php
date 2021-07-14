<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use function glob;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;

class Router implements IHandler
{
    public function register(IApplication $application) : void
    {
        $app = $application->getApp();

        foreach (glob($application->getBasePath() . '/config/routes/{*,*/,*/*/}*.php', GLOB_BRACE) as $collectionFile) {
            $app->mount(require $collectionFile);
        }
    }
}
