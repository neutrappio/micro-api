<?php

declare(strict_types=1);

namespace Mapi\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class RegisterValidator extends Validation
{
    public function initialize()
    {
        $this->add(
            'firstname',
            new StringLength([
                'max' => 26,
                'min' => 2,
                'messageMaximum' => 'The :field is too long',
                'messageMinimum' => 'The :field is too short',
            ])
        );

        $this->add(
            'lastname',
            new StringLength([
                'max' => 26,
                'min' => 2,
                'messageMaximum' => 'The :field is too long',
                'messageMinimum' => 'The :field is too short',
            ])
        );

        
            
        $this->add(
            'city',
            new StringLength([
                'max' => 30,
                'min' => 2,
                'messageMaximum' => 'The :field is too long',
                'messageMinimum' => 'The :field is too short',
                'allowEmpty' =>true
            ])
        );
        
        $this->add(
            'email',
            new PresenceOf(
                [
                    'message' => 'The e-mail is required',
                ]
            )
        );

        $this->add(
            'email',
            new Email(
                [
                    'message' => 'The e-mail is not valid',
                ]
            )
        );

        $this->add(
            'password',
            new PresenceOf(
                [
                    'message' => 'The password is required',
                ]
            )
        );

        $this->add(
            'password',
            new StringLength(
                [
                    'max' => 100,
                    'min' => 5,
                    'messageMaximum' => 'The :field is too long',
                    'messageMinimum' => 'The :field is too short',
                ]
            )
        );

        $this->add(
            'cpassword',
            new Confirmation([
                'with' => 'password',
                'message' => 'Password does not match confirmation',
            ])
        );

        $this->add(
            'agree',
            new InclusionIn([
            'domain' => [
                1
            ],
            'message' => 'You must agree to the terms and conditions',
        ])
        );
    }
}
