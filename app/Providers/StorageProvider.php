<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use Aws\S3\S3Client;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;

class StorageProvider extends Provider
{
    const SERVICE_NAME = "storage";

    public function init(IApplication $application) : Closure
    {
        return function () {
            $configs = $this->getConfig()->storage;
            return new S3Client($configs->toArray());
        };
    }
}
