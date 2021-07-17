<?php
declare(strict_types=1);

namespace Mapi\Controllers\User;

use Mapi\Models\User;
use Mapi\Controllers\Auth\LoggedController;

/**
 *  Landing Index Controller
 */
class ProfileController extends LoggedController
{
    /**
     * Index Action
     *
     * @return array
     */
    public function index() : array
    {
        return [
            'user'=> $this->getUser()
        ];
    }
}
