<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class SignupRequest
{
    #[Assert\NotBlank(message: 'Full name is required.')]
    #[Assert\Length(min: 4, max: 255, minMessage: 'Full name should be at least 4 characters.')]
    public string $fullName = '';

    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Please enter a valid email address.')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Password is required.')]
    #[Assert\Length(min: 8, minMessage: 'Password should be at least 8 characters.')]
    public string $password = '';

    #[Assert\NotBlank(message: 'User type is required.')]
    #[Assert\Choice(['buyer', 'seller'], message: 'User type must be either buyer or seller.')]
    public string $userType = '';

    #[Assert\NotBlank(message: 'Module is required.')]
    #[Assert\Choice(
        ['localFarming', 'buildingHousing', 'healthcare', 'carSales', 'foodServices'],
        message: 'Please select a valid module.'
    )]
    public string $module = '';

}
