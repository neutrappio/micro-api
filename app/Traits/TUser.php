<?php

namespace Mapi\Traits;

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
}
