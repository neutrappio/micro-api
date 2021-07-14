<?php

namespace Mapi\Interfaces;

use Closure;

interface IProvider
{
    /**
     * Get Service Name
     *
     * @return string
     */
    public function getName() : string;
    
    /**
     * Init Service Instance
     *
     * @return mixed
     */
    public function init(IApplication $application) : Closure;
}
