<?php

namespace Mapi\Traits;

use Lcobucci\JWT\Token;
use Phalcon\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Email as EmailValidator;

trait TUser
{
    /**
     * Validations and business logic
     *
     * @return boolean
     */

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        $validator->add(
            'email',
            new Uniqueness([
                'model' => $this,
                'message' => 'The email address is already taken',
            ])
        );

        return $this->validate($validator);
    }


    /**
     * before Create
     */
    public function beforeCreate() : void
    {
        parent::beforeCreate();

        $this->password = $this->hashPassword($this->password);
    }
    
    /**
     * Before Save
     */
    public function beforeUpdate() : void
    {
        parent::beforeUpdate();

        if ($this->hasChanged('password')) {
            $this->password = self::hashPassword($this->password);
        }
    }



    /**
    * Validate Password
    */
    public function validatePassword(string $password) : bool
    {
        return $this
            ->getDI()
            ->getSecurity()
            ->checkHash($password, $this->password);
    }


    /**
     * Hash Password
     */
    public static function hashPassword($password) : string
    {
        $security = new Security();
        return $security
            ->hash($password);
    }

    /**
     * Get the value of sessionToken
     *
     * @return  Token|null
     */
    public function getSessionToken() :? Token
    {
        return $this->sessionToken;
    }

    /**
     * Set the value of sessionToken
     *
     * @param  Token  $sessionToken
     *
     * @return  self
     */
    public function setSessionToken(Token $sessionToken) :? self
    {
        $this->sessionToken = $sessionToken;

        return $this;
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
}
