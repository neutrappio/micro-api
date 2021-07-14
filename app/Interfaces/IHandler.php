<?php

namespace Mapi\Interfaces;

interface IHandler
{
    public function register(IApplication $application) : void;
}
