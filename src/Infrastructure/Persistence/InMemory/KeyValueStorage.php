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

namespace Acme\App\Infrastructure\Persistence\InMemory;

use Acme\App\Core\Port\Persistence\KeyValueStorageInterface;

/**
 * @author Kasper Agg
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
final class KeyValueStorage implements KeyValueStorageInterface
{
    /** @var array */
    private $storage;

    public function __construct(array $data = [])
    {
        $this->storage = $data;
    }

    public function get(string $namespace, string $key): ?string
    {
        if (!$this->has($namespace, $key)) {
            return null;
        }

        return $this->storage[$this->createKey($namespace, $key)];
    }

    public function set(string $namespace, string $key, string $value): void
    {
        $this->storage[$this->createKey($namespace, $key)] = $value;
    }

    public function has(string $namespace, string $key): bool
    {
        return array_key_exists($this->createKey($namespace, $key), $this->storage);
    }

    public function remove(string $namespace, string $key): void
    {
        if ($this->has($namespace, $key)) {
            unset($this->storage[$this->createKey($namespace, $key)]);
        }
    }

    private function createKey(string $namespace, string $key): string
    {
        return sprintf('%s_%s', $namespace, $key);
    }
}
