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

namespace Acme\App\Infrastructure\Persistence\Session;

use Acme\App\Core\Port\Persistence\KeyValueStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Kasper Agg
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
final class KeyValueStorage implements KeyValueStorageInterface
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function get(string $namespace, string $key): ?string
    {
        return $this->session->get($this->createKey($namespace, $key));
    }

    public function set(string $namespace, string $key, string $value): void
    {
        $this->session->set($this->createKey($namespace, $key), $value);
    }

    public function has(string $namespace, string $key): bool
    {
        return $this->session->has($this->createKey($namespace, $key));
    }

    public function remove(string $namespace, string $key): void
    {
        $this->session->remove($this->createKey($namespace, $key));
    }

    private function createKey(string $namespace, string $key): string
    {
        return sprintf('%s_%s', $namespace, $key);
    }
}
