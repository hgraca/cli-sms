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

namespace Acme\PhpExtension\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class UuidGenerator
{
    /**
     * @var callable Must return a string representing the Uuid
     */
    private static $customGenerator;

    public static function generate(): Uuid
    {
        $customGenerator = self::$customGenerator;

        return new Uuid($customGenerator ? $customGenerator() : self::defaultGenerator());
    }

    public static function generateAsString(): string
    {
        $customGenerator = self::$customGenerator;

        return $customGenerator ? $customGenerator() : self::defaultGenerator();
    }

    /**
     * The callable must return a string representing the Uuid.
     */
    public static function overrideDefaultGenerator(callable $customGenerator): void
    {
        self::$customGenerator = $customGenerator;
    }

    public static function reset(): void
    {
        self::$customGenerator = null;
    }

    private static function defaultGenerator(): string
    {
        return RamseyUuid::uuid4()->toString();
    }
}
