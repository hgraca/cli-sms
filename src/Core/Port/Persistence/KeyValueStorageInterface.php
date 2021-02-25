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

namespace Acme\App\Core\Port\Persistence;

/**
 * @author Coen Moij
 * @author Kasper Agg
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
interface KeyValueStorageInterface
{
    public function get(string $namespace, string $key): ?string;

    public function set(string $namespace, string $key, string $value): void;

    public function has(string $namespace, string $key): bool;

    public function remove(string $namespace, string $key): void;
}
