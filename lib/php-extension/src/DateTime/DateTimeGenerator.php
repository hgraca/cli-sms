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

namespace Acme\PhpExtension\DateTime;

use Acme\PhpExtension\AbstractStaticClass;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

/**
 * The DateTimeGenerator is useful because it makes DateTime objects predictable and therefore testable.
 * Using DateTimeImmutable, provides for immutability, which helps reduce bugs.
 */
final class DateTimeGenerator extends AbstractStaticClass
{
    /**
     * @var callable
     */
    private static $customGenerator;

    /**
     * @throws DateTimeException
     */
    public static function generate(string $time = 'now', DateTimeZone $timezone = null): DateTimeImmutable
    {
        $customGenerator = self::$customGenerator;

        try {
            return $customGenerator
                ? $customGenerator($time, $timezone)
                : self::defaultGenerator($time, $timezone);
        } catch (Exception $e) {
            throw new DateTimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public static function overrideDefaultGenerator(callable $customGenerator): void
    {
        self::$customGenerator = $customGenerator;
    }

    public static function reset(): void
    {
        self::$customGenerator = null;
    }

    /**
     * @throws \Exception
     */
    private static function defaultGenerator(string $time = 'now', DateTimeZone $timezone = null): DateTimeImmutable
    {
        return new DateTimeImmutable($time, $timezone);
    }
}
