<?php

namespace Mapi;

use Mapi\Interfaces\IApplication;

/**
 * Api Class
 */
class Api
{
    /**
     * Application
     */
    private IApplication $app;

    /**
     * Handlers list
     *
     * @var array
     */
    private array $handlers = [];

    /**
     * Api
     *
     * @param IApplication $application
     * @param array $handlers
     *
     * @return void
     */
    public function __construct(IApplication $application, array $handlers = [])
    {
        $this->app = $application;
        $this->handlers = $handlers;
    }

    /**
     * Register handlers
     *
     * @return void
     */
    public function register() : void
    {
        foreach ($this->handlers as $handler) {
            (new $handler())->register($this->app);
        }
    }
}
