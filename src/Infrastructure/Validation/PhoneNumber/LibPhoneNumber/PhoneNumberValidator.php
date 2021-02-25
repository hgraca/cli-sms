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

namespace Acme\App\Infrastructure\Validation\PhoneNumber\LibPhoneNumber;

use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberCouldNotBeParsedException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberInvalidException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberValidatorInterface;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

final class PhoneNumberValidator implements PhoneNumberValidatorInterface
{
    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * @var string
     */
    private $countryCode;

    public function __construct(PhoneNumberUtil $phoneNumberUtil, string $countryCode)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->countryCode = $countryCode;
    }

    public function validate(string $phoneNumber, string $countryCode = null): void
    {
        try {
            $number = $this->phoneNumberUtil->parse($phoneNumber, $countryCode ?? $this->countryCode);
        } catch (NumberParseException $exception) {
            throw new PhoneNumberCouldNotBeParsedException($phoneNumber);
        }

        if (!$this->phoneNumberUtil->isValidNumber($number)) {
            throw new PhoneNumberInvalidException($phoneNumber);
        }
    }
}
