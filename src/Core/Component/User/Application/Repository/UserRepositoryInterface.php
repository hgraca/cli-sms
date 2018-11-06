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

namespace Acme\App\Core\Component\User\Application\Repository;

use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Core\Port\Persistence\ResultCollectionInterface;
use Acme\App\Core\SharedKernel\Component\User\Domain\User\UserId;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function remove(User $user): void;

    /**
     * @return User[]
     */
    public function findAll(array $orderBy, int $maxResults): ResultCollectionInterface;

    public function findOneByUsername(string $username): User;

    public function findOneByEmail(string $email): User;

    public function findOneById(UserId $id): User;
}
