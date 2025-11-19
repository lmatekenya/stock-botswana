<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Please enter a valid email address.')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Password is required.')]
    #[Assert\Length(min: 6, minMessage: 'Password should be at least 6 characters.')]
    public string $password = '';

}
