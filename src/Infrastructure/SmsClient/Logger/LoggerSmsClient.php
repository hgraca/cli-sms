<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used for on-boarding of new developers into Werkspot dev teams.
 *
 * (c) Werkspot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\App\Infrastructure\SmsClient\Logger;

use Acme\App\Core\Port\SmsClient\Exception\SmsClientException;
use Acme\App\Core\Port\SmsClient\Sms;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberCouldNotBeParsedException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberInvalidException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberValidatorInterface;
use Exception;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;

/**
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
final class LoggerSmsClient
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

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $sender;

    public function __construct(
        LoggerInterface $logger,
        PhoneNumberValidatorInterface $phoneNumberValidator,
        PhoneNumberUtil $phoneNumberUtil,
        string $countryCode,
        string $smsSender,
        string $defaultDestination = null
    ) {
        $this->phoneNumberValidator = $phoneNumberValidator;
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->countryCode = $countryCode;
        $this->defaultDestination = $defaultDestination;
        $this->sender = $smsSender;
        $this->logger = $logger;
    }

    public function sendSms(Sms $sms): void
    {
        $phoneNumber = $this->defaultDestination ?? $sms->getPhoneNumber();

        try {
            $this->phoneNumberValidator->validate($phoneNumber);
        } catch (PhoneNumberException $e) {
            throw new PhoneNumberInvalidException($phoneNumber);
        }

        try {
            $phoneNumberObject = $this->phoneNumberUtil->parse($phoneNumber, $this->countryCode);
        } catch (NumberParseException $exception) {
            throw new PhoneNumberCouldNotBeParsedException($phoneNumber);
        }

        $phoneNumber = $this->phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
        $content = $sms->getContent();

        try {
            $this->logger->info("Sms sent from '{$this->sender}' to '$phoneNumber': $content");
        } catch (Exception $e) {
            throw new SmsClientException($e->getMessage(), 0, $e);
        }
    }
}
