<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use function gettype;
use function htmlentities;
use function strpos;


use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;

class Sanitize implements IHandler
{
    public function register(IApplication $application) : void
    {
        $app = $application->getApp();
        
        $app->before(function () use ($app) {
            $contentType = $app->request->getHeader('Content-Type');
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (-1 !== strpos($contentType, '/json')) {
                $rawBody = $app->request->getJsonRawBody(true) ?? [];
                // inject params in the request

                foreach ($rawBody as $key => $value) {
                    $_REQUEST[$key] = $value;
                }
            }

            // Sanitize
            foreach ($_REQUEST as $key => $value) {
                if (gettype($value) === 'string') {
                    $_REQUEST[$key] = htmlentities($value);
                }
            }
        });
    }
}
