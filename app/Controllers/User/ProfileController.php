<?php
declare(strict_types=1);

namespace Mapi\Controllers\User;

use Mapi\Models\User;
use Mapi\Controllers\Auth\LoggedController;
use Mapi\Traits\TPagination;

/**
 *  Landing Index Controller
 */
class ProfileController extends LoggedController
{
    use TPagination;

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
