<?php
declare(strict_types=1);

namespace Mapi;

use Phalcon\Mvc\Micro;
use Phalcon\Di\DiInterface;
use Phalcon\Di\FactoryDefault;
use Mapi\Interfaces\IApplication;

class Application implements IApplication
{
    
    /**
     * Micro App
     *
     * @var Micro
     */
    private Micro $app;
    
    /**
     * Api App
     *
     * @var Api
     */
    private Api $api;

    /**
     * Di Facotry
     *
     * @var DiInterface
     */
    private DiInterface $DiFactory;

    /**
     * Services list
     *
     * @var array
     */
    private array $services = [];

    /**
     * Handlers list
     *
     * @var array
     */
    private array $handlers = [];

    /**
     * middlewares list
     *
     * @var array
     */
    private array $middlewares = [];

    /**
     * App Constructor
     */
    public function __construct()
    {
        $this->DiFactory = new FactoryDefault();
    }

    /**
     * Get Project Base Path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return BASE_PATH;
    }

    /**
     * Get Application Path
     *
     * @return string
     */
    public function getAppPath(): string
    {
        return APP_PATH;
    }

    /**
     * Get di Facotry
     *
     * @return  DiInterface
     */
    public function getDiFactory() : DiInterface
    {
        return $this->DiFactory;
    }

    /**
     * Load Services
     *
     * @return void
     */
    public function loadServices() : void
    {
        foreach ($this->services as $serviceClass) {
            $service = new $serviceClass();
            $this->DiFactory->setShared($service->getName(), $service->init($this));
        }
    }

    /**
     * Init Application
     *
     * @return void
     */
    private function initApp() : void
    {
        $this->app = new Micro($this->DiFactory);
        $this->api = new Api($this, $this->handlers);

        $this->loadServices();
        $this->api->register();
    }

    /**
     * Set the value of services
     *
     * @return  self
     */
    public function setServices(array $services)
    {
        foreach ($services as $service) {
            $this->services[] = $service;
        }

        return $this;
    }

    /**
     * Set handlers list
     *
     * @param  array  $handlers  Handlers list
     *
     * @return  self
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;

        return $this;
    }

    /**
     * Set middlewares list
     *
     * @param  array  $middlewares  middlewares list
     *
     * @return  self
     */
    public function setMiddlewares(array $middlewares)
    {
        $this->middlewares = $middlewares;

        return $this;
    }

    /**
     * Run App
     *
     * @return void
     */
    public function run(string $url = "/") : void
    {
        $this->initApp();


        $this->app->handle($url);
    }

    /**
     * Get micro App
     *
     * @return  Micro
     */
    public function getApp() : Micro
    {
        return $this->app;
    }

    /**
     * Get middlewares list
     *
     * @return  array
     */
    public function getMiddlewares() : array
    {
        return $this->middlewares;
    }
}
