<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;

class Finish implements IHandler
{
    public function register(IApplication $application) : void
    {
        $app = $application->getApp();
        
        $app->finish(function () use ($app) {

            //Finally, send the prepared response, flush output buffers (HTTP header)
            !$app->response->isSent() && $app->response->send();

            //Stops the middleware execution avoiding than other middleware be executed
            $app->stop();
        });
    }
}
