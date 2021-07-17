<?php
declare(strict_types=1);

namespace Mapi\Providers;

use Closure;

use function strtolower;
use function array_keys;

use Mapi\Core\Provider;
use Mapi\Interfaces\IApplication;
use Mapi\Libraries\Http\HttpCodes;
use Mapi\Exceptions\PublicException;

class DatabaseProvider extends Provider
{
    const SERVICE_NAME = "db";

    public function init(IApplication $application) : Closure
    {
        return function () {
            $config = $this->getConfig();

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;

            $params = $config->database->toArray();

            if (strtolower($config->database->adapter) == 'postgresql') {
                foreach (array_keys($params) as $key) {
                    if (!in_array($key, ['host','port','username','password','dbname','schema'])) {
                        unset($params[$key]);
                    }
                }
            }

            try {
                $connection = new $class($params);
            } catch (\Exception $e) {
                // throw new public exception , let app handle it
                throw new PublicException("Database Connection Failed", HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $connection;
        };
    }
}
