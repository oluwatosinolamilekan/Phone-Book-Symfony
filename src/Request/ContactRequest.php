<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;

class ContactRequest extends BaseRequest
{
    #[NotBlank()]
    protected $first_name;

    #[NotBlank()]
    protected $last_name;

    #[NotBlank()]
    protected $address;

    #[NotBlank()]
    protected $phone_number;

    #[NotBlank()]
    protected $birthday;

    #[NotBlank()]
    protected $email;
}