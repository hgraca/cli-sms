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

namespace Acme\App\Core\Port\Auth;

interface AuthorizationServiceInterface
{
    public const ROLE_AUTHENTICATED = 'IS_AUTHENTICATED_FULLY';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ACTION_SHOW = 'show';
    public const ACTION_EDIT = 'edit';
    public const ACTION_DELETE = 'delete';

    public function hasRole(string ...$role): bool;

    public function isAllowed(string $action, $subject): bool;

    /**
     * Throws an exception unless the specified roles and action are met on the subject.
     *
     * @throws AccessDeniedException
     */
    public function denyAccessUnlessGranted(
        array $roleList = [],
        string $action = '',
        string $message = 'Access Denied.',
        $subject = null
    ): void;
}
