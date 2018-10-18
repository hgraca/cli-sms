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

namespace Acme\App\Core\Component\Message\Domain\Message;

use Acme\App\Core\SharedKernel\Component\Message\Domain\Message\MessageId;
use DateTimeImmutable;
use Exception;

final class Message
{
    /**
     * @var MessageId
     */
    private $id;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTimeImmutable
     */
    private $sentAt;

    /**
     * @throws Exception
     */
    public function __construct(string $recipient, string $message)
    {
        $this->id = new MessageId();
        $this->recipient = $recipient;
        $this->message = $message;
        $this->sentAt = new DateTimeImmutable();
    }

    public function getId(): MessageId
    {
        return $this->id;
    }
}
