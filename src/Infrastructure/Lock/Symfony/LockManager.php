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

namespace Acme\App\Infrastructure\Lock\Symfony;

use Acme\App\Core\Port\Lock\LockAlreadyExistsException;
use Acme\App\Core\Port\Lock\LockManagerInterface;
use Acme\App\Core\Port\Lock\LockNotFoundException;
use Symfony\Component\Lock\Lock;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Lock\Store\FlockStore;

final class LockManager implements LockManagerInterface
{
    /**
     * @var Lock[]
     */
    private $lockList = [];

    /**
     * @var LockFactory
     */
    private $factory;

    public function __construct()
    {
        $this->factory = new LockFactory(new FlockStore(sys_get_temp_dir()));
    }

    public function acquire(string $resourceName): void
    {
        $lock = $this->hasLock($resourceName)
            ? $this->getLock($resourceName)
            : $this->createLock($resourceName);
        $lock->acquire(true);
    }

    public function releaseAll(): void
    {
        foreach ($this->lockList as $resourceName => $lock) {
            $lock->release();
        }
    }

    private function createLock(string $resourceName): LockInterface
    {
        if (!$this->hasLock($resourceName)) {
            $this->storeLock($resourceName, $this->factory->createLock($resourceName));
        }

        return $this->getLock($resourceName);
    }

    private function storeLock(string $resourceName, LockInterface $lock): void
    {
        if ($this->hasLock($resourceName)) {
            throw new LockAlreadyExistsException();
        }
        $this->lockList[$resourceName] = $lock;
    }

    private function getLock(string $resourceName): LockInterface
    {
        if (!$this->hasLock($resourceName)) {
            throw new LockNotFoundException();
        }

        return $this->lockList[$resourceName];
    }

    private function hasLock(string $resourceName): bool
    {
        return isset($this->lockList[$resourceName]);
    }
}
