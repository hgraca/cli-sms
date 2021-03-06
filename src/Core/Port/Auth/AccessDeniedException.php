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

final class AccessDeniedException extends AppRuntimeException
{
    /**
     * @var string[]
     */
    private $roleList;

    /**
     * @var string
     */
    private $action;

    /**
     * @var mixed|null
     */
    private $subject;

    /**
     * @param string[]   $roleList
     * @param mixed|null $subject
     */
    public function __construct(
        array $roleList = [],
        string $action = '',
        $subject = null,
        string $message = 'Access Denied.'
    ) {
        parent::__construct($message, 403);
        $this->roleList = $roleList;
        $this->action = $action;
        $this->subject = $subject;
    }

    /**
     * @return string[]
     */
    public function getRoleList(): array
    {
        return $this->roleList;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return mixed|null
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
