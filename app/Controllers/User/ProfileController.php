<?php
declare(strict_types=1);

namespace Mapi\Controllers\User;

use Mapi\Core\Controller;
use Mapi\Models\User;

/**
 *  Landing Index Controller
 */
class ProfileController extends Controller
{
    /**
     * Index Action
     *
     * @return array
     */
    public function index() : array
    {
        return [
            'user'=> User::findFirst()
        ];
    }

    /**
     * All users
     *
     * @return array
     */
    public function all() : array
    {
        return [
            'data'=> User::find(['limit'=> 10])
        ];
    }
}
