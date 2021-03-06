<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use Phalcon\Url\UrlResolver;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;

class UrlProvider extends Provider
{
    const SERVICE_NAME = "config";

    public function getName() : string
    {
        return self::SERVICE_NAME;
    }

    public function init(IApplication $application) : Closure
    {
        return function () {
            $config = $this->getConfig();

            $url = new UrlResolver();
            $url->setBaseUri($config->application->baseUri);

            return $url;
        };
    }
}
