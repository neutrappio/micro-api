<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use Phalcon\Security\Random;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;

class UtilsProvider extends Provider
{
    const SERVICE_NAME = "utils";

    private IApplication $application;

    public function init(IApplication $application) : Closure
    {
        $service = $this;

        $service->application = $application;

        return function () use ($service) {
            return $service;
        };
    }

    /**
     * Util: Random Instance
     *
     * @return Random
     */
    public function random() : Random
    {
        return new Random();
    }
}
