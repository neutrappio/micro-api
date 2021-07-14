<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use function defined;
use function is_array;
use function is_object;
use function microtime;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;

class Response implements IHandler
{
    public function register(IApplication $application) : void
    {
        $app = $application->getApp();

        $app->after(function () use ($app) {
            $content = $app->getReturnedValue();
            $content = is_array($content) || is_object($content) ? $content : ['data' => $content];

            /**
             * Dynamic Response Content & Type
             */
            $app->response->setContentType('application/json');
            $app->response->setJsonContent(array_merge_recursive(
                [
                    'status' => $app->response->getStatusCode() ?: "ok"
                ],
                is_array($content) ? $content : $content->toArray(),
                [
                    '_time'=> defined('START_TIME') ? ((microtime(true)- START_TIME) / 1000) . "ms" : -1
                ]
            ));

            /**
             * End & Send Response
             */
            $app->response->send();
        });
    }
}
