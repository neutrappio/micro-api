<?php

namespace Mapi\Interfaces;

use Phalcon\Mvc\Micro;
use Phalcon\Di\DiInterface;

interface IApplication
{
    /**
     * Get Di Factory
     *
     * @return DiInterface
     */
    public function getDiFactory() : DiInterface;

    /**
     * Get Application Path
     *
     * @return string
     */
    public function getAppPath() : string;

    /**
     * Get Project Base Path
     *
     * @return string
     */
    public function getBasePath() : string;


    /**
     * Get Application
     *
     * @return Micro
     */
    public function getApp() : Micro;
}
