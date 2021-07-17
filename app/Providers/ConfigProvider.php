<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;

class ConfigProvider extends Provider
{
    const SERVICE_NAME = "config";

    public function init(IApplication $application) : Closure
    {
        return function () use ($application) {
            return include $application->getBasePath() . "/config/config.php";
        };
    }
}
