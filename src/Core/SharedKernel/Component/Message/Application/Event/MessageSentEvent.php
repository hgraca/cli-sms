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

namespace Acme\App\Core\SharedKernel\Component\Message\Application\Event;

use Acme\App\Core\SharedKernel\Component\Message\Domain\Message\MessageId;
use Acme\App\Core\SharedKernel\Port\EventDispatcher\EventInterface;

final class MessageSentEvent implements EventInterface
{
    /**
     * @var MessageId
     */
    private $messageId;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $content;

    public function __construct(MessageId $messageId, string $recipient, string $content)
    {
        $this->messageId = $messageId;
        $this->recipient = $recipient;
        $this->content = $content;
    }

    public function getMessageId(): MessageId
    {
        return $this->messageId;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
