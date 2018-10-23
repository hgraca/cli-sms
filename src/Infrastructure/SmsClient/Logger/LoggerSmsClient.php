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

namespace Acme\App\Infrastructure\SmsClient\Logger;

use Acme\App\Core\Port\SmsClient\Exception\SmsClientException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberValidatorInterface;
use Acme\App\Infrastructure\SmsClient\AbstractClient;
use Exception;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;

/**
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
final class LoggerSmsClient extends AbstractClient
{
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
        parent::__construct($phoneNumberValidator, $phoneNumberUtil, $countryCode, $defaultDestination);
        $this->sender = $smsSender;
        $this->logger = $logger;
    }

    public function triggerSms(string $phoneNumber, string $content): void
    {
        try {
            $this->logger->info("Sms sent from '{$this->sender}' to '$phoneNumber': $content");
        } catch (Exception $e) {
            throw new SmsClientException($e->getMessage(), 0, $e);
        }
    }
}
