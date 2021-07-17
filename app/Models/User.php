<?php

namespace Mapi\Models;

use Mapi\Core\Model;
use Mapi\Traits\TUser;

class User extends Model
{
    use TUser;

    /**
     * Basic Fields
     * @var array
     */
    const BASIC_FIELDS = ['id', 'firstname', 'lastname', 'username', 'avatar', 'phone',  'email', 'created_at', 'updated_at'];

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $avatar;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $created_ip;

    /**
     *
     * @var string
     */
    public $updated_ip;

    /**
     * Exclude password value
     *
     * @var boolean
     */
    public $excludePassword = true;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->setSource("user");
    }

    /**
     * To Array method
     *
     * @param boolean $excludePassword
     * @return array
     */
    public function toArray($columns = null): array
    {
        $data = parent::toArray();

        if ($this->excludePassword) {
            unset($data['password']);
        }
        return $data;
    }


    /**
     * Get User Profile
     *
     * @return array
     */
    public function getProfile() : array
    {
        return $this->getData();
    }
}
