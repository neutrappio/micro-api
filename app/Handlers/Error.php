<?php
declare(strict_types=1);

namespace Mapi\Handlers;

use function getenv;
use function get_class;
use function array_merge;
use function is_subclass_of;
use function property_exists;

use Mapi\Interfaces\IHandler;
use Mapi\Interfaces\IApplication;
use Mapi\Libraries\Http\HttpCodes;

class Error implements IHandler
{
    public function register(IApplication $application) : void
    {
        $app = $application->getApp();

        $app->error(
            function ($e) use ($app) {
                $codeError = $e->getCode() ?: HttpCodes::HTTP_BAD_GATEWAY;

                $app->response->setContentType('application/json');
                $app->response->setJsonContent(
                    array_merge(
                        getenv('APP_ENV') === 'production' && (!is_subclass_of($e, PublicException::class, true) && get_class($e) !== PublicException::class) ? [
                    'code'    => $codeError,
                    'status'  => 'error',
                    'message' => 'Something went wrong please contact support',
                ]
                :  [
                    'code'    => $codeError,
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTrace()
                ],
                        (property_exists(get_class($e), 'data') ? $e->getData() : [])
                    )
                );
        
                /**
                 * Aditions Exception Headers
                 */
                if (property_exists(get_class($e), 'headers')) {
                    foreach ($e->getHeaders() as $hname => $hvalue) {
                        $app->response->setHeader($hname, $hvalue);
                    }
                }
        
                /**
                 * Dynamic response messages
                 */
                $statusCode = $app->response->getStatusCode() ?: $codeError;
                $app->response->setStatusCode(
                    HttpCodes::parseCode($statusCode),
                    HttpCodes::getMessageByCode($statusCode)
                );
    
                $app->response->send();
            }
        );
    }
}
