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

namespace Acme\App\Core\Component\Message\Application\Service;

use Acme\App\Core\Component\Message\Application\Repository\MessageRepositoryInterface;
use Acme\App\Core\Component\Message\Domain\Message\Message;
use Acme\App\Core\Port\EventDispatcher\EventDispatcherInterface;
use Acme\App\Core\SharedKernel\Component\Message\Application\Event\MessageSentEvent;
use Exception;

final class MessageService
{
    /**
     * @var MessageRepositoryInterface
     */
    private $smsRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        MessageRepositoryInterface $smsRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->smsRepository = $smsRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws Exception
     */
    public function send(string $phoneNumber, string $messageText): void
    {
        $message = new Message($phoneNumber, $messageText);
        $this->smsRepository->add($message);
        $this->eventDispatcher->dispatch(new MessageSentEvent($message->getId(), $phoneNumber, $messageText));
    }
}
