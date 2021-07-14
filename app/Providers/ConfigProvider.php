<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;
use Mapi\Interfaces\IProvider;
use Mapi\Interfaces\IApplication;

class ConfigProvider implements IProvider
{
    const SERVICE_NAME = "config";
    
    public function getName() : string
    {
        return self::SERVICE_NAME;
    }

    public function init(IApplication $application) : Closure
    {
        return function () use ($application) {
            include $application->getBasePath() . "/config/config.php";
        };
    }
}
