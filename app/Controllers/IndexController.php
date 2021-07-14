<?php

namespace Mapi\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function hellostring()
    {
        return 'hello world ! string';
    }

    public function helloarray()
    {
        return ['array'=> 'hello world ! array '];
    }
}
