<?php
declare(strict_types=1);

namespace Mapi\Controllers\User;

use Mapi\Models\User;
use Mapi\Controllers\Auth\LoggedController;
use Mapi\Traits\TPagination;
use Mapi\Traits\TResponse;

/**
 *  Landing Index Controller
 */
class ProfileController extends LoggedController
{
    use TResponse;
    use TPagination;

    /**
     * Self Profile
     *
     * @return array
     */
    public function me() : array
    {
        return $this->getAsItem($this->getUser());
    }
    
    /**
     * Get List of all users
     *
     * @return array
     */
    public function all(): array
    {
        $builder = $this
            ->modelsManager
            ->createBuilder()
            ->columns('id, firstname, lastname, avatar')
            ->from(User::class)
            ->orderBy('firstname, lastname')
            ;

        return $this->getFullPagination($builder);
    }
}
