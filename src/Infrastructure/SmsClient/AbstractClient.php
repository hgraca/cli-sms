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

namespace Acme\App\Infrastructure\SmsClient;

use Acme\App\Core\Port\SmsClient\Sms;
use Acme\App\Core\Port\SmsClient\SmsClientInterface;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberCouldNotBeParsedException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberValidatorInterface;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * @author Nicolae Nichifor
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
abstract class AbstractClient implements SmsClientInterface
{
    /**
     * @var PhoneNumberValidatorInterface
     */
    private $phoneNumberValidator;

    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $defaultDestination;

    public function __construct(
        PhoneNumberValidatorInterface $phoneNumberValidator,
        PhoneNumberUtil $phoneNumberUtil,
        string $countryCode,
        string $defaultDestination = null
    ) {
        $this->phoneNumberValidator = $phoneNumberValidator;
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->countryCode = $countryCode;
        $this->defaultDestination = $defaultDestination;
    }

    /**
     * @throws PhoneNumberException
     */
    public function sendSms(Sms $sms): void
    {
        $phoneNumber = $this->formatToE164($this->defaultDestination ?? $sms->getPhoneNumber());

        $this->triggerSms($phoneNumber, $sms->getContent());
    }

    abstract public function triggerSms(string $phoneNumber, string $content): void;

    /**
     * {@inheritdoc}
     *
     * @throws PhoneNumberException
     */
    private function formatToE164(string $phoneNumber): string
    {
        $phoneNumberObject = $this->parsePhoneNumberOrThrowException($phoneNumber);

        return $this->phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
    }

    /**
     * @throws PhoneNumberException
     */
    private function parsePhoneNumberOrThrowException(string $phoneNumber): PhoneNumber
    {
        $this->phoneNumberValidator->validate($phoneNumber);

        try {
            return $this->phoneNumberUtil->parse($phoneNumber, $this->countryCode);
        } catch (NumberParseException $exception) {
            throw new PhoneNumberCouldNotBeParsedException($phoneNumber);
        }
    }
}
