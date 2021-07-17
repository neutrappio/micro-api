<?php
declare(strict_types=1);

use Phalcon\Loader;
use Mapi\Application;
use Mapi\Exceptions\PublicException;

define('START_TIME', microtime(true));
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

(new Loader())->registerNamespaces([ 'Mapi' => APP_PATH ])->register();

$app = new Application();
$app->setServices(require $app->getBasePath() . '/config/services.php');
$app->setHandlers(require $app->getBasePath() . '/config/handlers.php');
$app->setMiddlewares(require $app->getBasePath() . '/config/middlewares.php');


try {
    /**
     * Handle the request
     */
    $app->run($_SERVER['REQUEST_URI']);
} catch (PublicException $e) {
} catch (\Exception $e) {
    // Logger
    echo "Error : ", $e->getMessage();
}
