<?php

namespace Mapi\Core;

use Mapi\Interfaces\IProvider;

/**
 * Abstract Provider class
 */
abstract class Provider implements IProvider
{
    const SERVICE_NAME = 'ops';

    public function getName() : string
    {
        return $this::SERVICE_NAME;
    }
}
