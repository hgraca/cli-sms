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

namespace Acme\App\Core\Component\User\Application\Repository\InMemory;

use Acme\App\Core\Component\User\Application\Repository\UserRepositoryInterface;
use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Core\Port\Persistence\Exception\EmptyQueryResultException;
use Acme\App\Core\SharedKernel\Component\User\Domain\User\UserId;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private $userList = [];

    public function add(User $user): void
    {
        $this->userList[$user->getId()->toScalar()] = $user;
    }

    public function remove(User $user): void
    {
        unset($this->userList[$user->getId()->toScalar()]);
    }

    public function findOneByUsername(string $username): User
    {
        foreach ($this->userList as $user) {
            if ($user->getUsername() === $username) {
                return $user;
            }
        }

        throw new EmptyQueryResultException();
    }

    public function findOneByEmail(string $email): User
    {
        foreach ($this->userList as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }

        throw new EmptyQueryResultException();
    }

    public function findOneById(UserId $id): User
    {
        if (!isset($this->userList[$id->toScalar()])) {
            throw new EmptyQueryResultException();
        }

        return $this->userList[$id->toScalar()];
    }
}
