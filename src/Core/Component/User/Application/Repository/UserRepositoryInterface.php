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

namespace Acme\App\Core\Component\User\Application\Repository;

use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Core\SharedKernel\Component\User\Domain\User\UserId;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function remove(User $user): void;

    public function findOneByUsername(string $username): User;

    public function findOneByEmail(string $email): User;

    public function findOneById(UserId $id): User;

    public function findOneByMobile(string $mobile): User;
}
