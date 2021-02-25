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

namespace Acme\App\Infrastructure\Auth\Symfony;

use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Core\Port\Auth\UserSecretEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as SymfonyUserPasswordEncoderInterface;

final class UserSecretEncoder implements UserSecretEncoderInterface
{
    /**
     * @var SymfonyUserPasswordEncoderInterface
     */
    private $symfonyEncoder;

    public function __construct(SymfonyUserPasswordEncoderInterface $symfonyEncoder)
    {
        $this->symfonyEncoder = $symfonyEncoder;
    }

    public function encode(string $secret, User $user): string
    {
        return $this->symfonyEncoder->encodePassword(SecurityUser::fromUser($user), $secret);
    }
}
