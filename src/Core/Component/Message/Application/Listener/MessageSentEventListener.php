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

namespace Acme\App\Core\Component\Message\Application\Listener;

use Acme\App\Core\Port\SmsClient\Sms;
use Acme\App\Core\Port\SmsClient\SmsClientInterface;
use Acme\App\Core\SharedKernel\Component\Message\Application\Event\MessageSentEvent;

final class MessageSentEventListener
{
    /**
     * @var SmsClientInterface
     */
    private $smsClient;

    public function __construct(SmsClientInterface $smsClient)
    {
        $this->smsClient = $smsClient;
    }

    public function sendSms(MessageSentEvent $messageSentEvent): void
    {
        $this->smsClient->sendSms(new Sms($messageSentEvent->getContent(), $messageSentEvent->getRecipient()));
    }
}
