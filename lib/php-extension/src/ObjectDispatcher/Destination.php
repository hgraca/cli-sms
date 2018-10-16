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

namespace Acme\PhpExtension\ObjectDispatcher;

/**
 * This is just a DTO, it only has getters, theres no logic to test, so we ignore it for code coverage purposes.
 *
 * @codeCoverageIgnore
 */
final class Destination
{
    /**
     * @var callable
     */
    private $receiver;

    /**
     * @var int|null
     */
    private $priority;

    public function __construct(callable $receiver, ?int $priority = null)
    {
        $this->receiver = $receiver;
        $this->priority = $priority;
    }

    public function getReceiver(): callable
    {
        return $this->receiver;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }
}
