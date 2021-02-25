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

use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Core\SharedKernel\Component\User\Domain\User\UserId;
use Psr\Http\Message\ServerRequestInterface;

interface AuthenticationServiceInterface
{
    public function isCsrfTokenValid(string $id, string $token): bool;

    public function getLoggedInUserId(): UserId;

    public function getLoggedInUser(): User;

    public function getLastAuthenticationError(
        ServerRequestInterface $request,
        bool $clearSession = true
    ): ?AuthenticationException;

    public function getLastAuthenticationUsername(ServerRequestInterface $request): string;
}
