<?php
declare(strict_types=1);

namespace Mapi\Controllers\Auth;

use Mapi\Models\User;

use Mapi\Core\Controller;

/**
 *  Client Auth Controller
 */
class LoggedController extends Controller
{
    /** @var string */
    const AUTH_KEY = 'user';

    /**
     * Logged User
     *
     * @var User
     */
    protected User $user;


    /**
     * On Construct , Bind User into controller
     *
     * @return void
     */
    public function onConstruct() : void
    {
        if ($this->getDI()->has(self::AUTH_KEY)) {
            $this->user = $this->getDI()->get(self::AUTH_KEY);
        }
    }

    /**
     * Get the value of user
     */
    public function getUser() : User
    {
        return $this->user;
    }
}
