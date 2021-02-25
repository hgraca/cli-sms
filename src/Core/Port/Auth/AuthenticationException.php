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

namespace Acme\App\Core\Port\Auth;

use Acme\App\Core\SharedKernel\Exception\AppRuntimeException;
use Throwable;

final class AuthenticationException extends AppRuntimeException
{
    /**
     * @var string
     */
    private $messageKey;

    /**
     * @var array
     */
    private $messageData;

    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null,
        string $messageKey = '',
        array $messageData = []
    ) {
        parent::__construct($message, $code, $previous);

        $this->messageKey = $messageKey;
        $this->messageData = $messageData;
    }

    public function getMessageKey(): string
    {
        return $this->messageKey;
    }

    public function getMessageData(): array
    {
        return $this->messageData;
    }
}
