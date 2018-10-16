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

namespace Acme\App\Infrastructure\SmsClient\MessageBird;

use Acme\App\Core\Port\SmsClient\Exception\SmsClientException;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberValidatorInterface;
use Acme\App\Infrastructure\SmsClient\AbstractClient;
use libphonenumber\PhoneNumberUtil;
use MessageBird\Client;
use MessageBird\Exceptions\MessageBirdException;
use MessageBird\Objects\Message;

/**
 * @author Winfred Peereboom
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
final class MessageBirdClient extends AbstractClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $sender;

    public function __construct(
        PhoneNumberValidatorInterface $phoneNumberValidator,
        PhoneNumberUtil $phoneNumberUtil,
        string $countryCode,
        Client $client,
        string $smsSender,
        string $defaultDestination = null
    ) {
        parent::__construct($phoneNumberValidator, $phoneNumberUtil, $countryCode, $defaultDestination);
        $this->client = $client;
        $this->sender = $smsSender;
    }

    public function triggerSms(string $phoneNumber, string $content): void
    {
        $message = new Message();
        $message->originator = $this->sender;
        $message->recipients = [$phoneNumber];
        $message->body = $content;

        try {
            $this->client->messages->create($message);
        } catch (MessageBirdException $e) {
            throw new SmsClientException($e->getMessage(), 0, $e);
        }
    }
}
