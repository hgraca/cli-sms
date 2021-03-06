<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used in a workshop to explain Architecture patterns.
 *
 * Most of it authored by Herberto Graca.
 */

namespace Acme\App\Core\Component\User\Application\Validation;

use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberValidatorInterface;
use Acme\App\Core\SharedKernel\Exception\InvalidArgumentException;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
class UserValidationService
{
    /**
     * @var PhoneNumberValidatorInterface
     */
    private $phoneNumberValidator;

    public function __construct(PhoneNumberValidatorInterface $phoneNumberValidator)
    {
        $this->phoneNumberValidator = $phoneNumberValidator;
    }

    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (preg_match('/^[a-z_]+$/', $username) !== 1) {
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (mb_strlen(trim($plainPassword)) < 6) {
            throw new InvalidArgumentException('The password must be at least 6 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (mb_strpos($email, '@') === false) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    /**
     * @throws PhoneNumberException
     */
    public function validateMobile(?string $mobile): string
    {
        $this->phoneNumberValidator->validate($mobile);

        return $mobile;
    }

    public function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
            throw new InvalidArgumentException('The full name can not be empty.');
        }

        return $fullName;
    }
}
