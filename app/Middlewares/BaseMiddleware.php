<?php

namespace Mapi\Middlewares;

use Exception;
use function explode;
use function array_search;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class BaseMiddleware implements MiddlewareInterface
{
    protected Micro $app;

    /**
     * Call
     *
     * @param Micro $app
     * @return void
     */
    public function call(Micro $app)
    {
        $this->app = $app;
        return true;
    }

    /**
     * Get Route Name
     */
    public function getRouteName(Micro $app) :? string
    {
        $router = $app->router;
        if (null === $router || null === $router->getMatchedRoute()) {
            throw new Exception("Router is missing to get route name");
        }
        return $router->getMatchedRoute()->getName();
    }

    /**
     * Regex Matched
     *
     * @param Micro $app
     * @param string $key
     * @return boolean
     */
    protected function matchRoute(Micro $app, string $key) : bool
    {
        $keys = explode(".", $this->getRouteName($app));
        $search = array_search($key, $keys);
        return false !== $search;
    }
}
