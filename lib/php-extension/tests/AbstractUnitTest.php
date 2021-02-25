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

namespace Acme\PhpExtension\Test;

use Acme\PhpExtension\DateTime\DateTimeGenerator;
use Acme\PhpExtension\Uuid\UuidGenerator;
use ArrayAccess;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * A unit test will test a method, class or set of classes in isolation from the tools and delivery mechanisms.
 * How isolated the test needs to be, it depends on the situation and how you decide to test the application.
 * However, it is important to note that these tests do not need to boot the application.
 */
abstract class AbstractUnitTest extends TestCase
{
    /**
     * @after
     */
    public function resetDateTimeGenerator(): void
    {
        DateTimeGenerator::reset();
    }

    /**
     * @after
     */
    public function resetUuidGenerator(): void
    {
        UuidGenerator::reset();
    }

    /**
     * Asserts that an array has a specified subset.
     *
     * @param array|ArrayAccess $subset
     * @param array|ArrayAccess $array
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @codeCoverageIgnore
     */
    public static function assertArraySubset($subset, $array, bool $checkForObjectIdentity = false, string $message = ''): void
    {
        if (!(is_array($subset) || $subset instanceof ArrayAccess)) {
            throw InvalidArgumentException::create(1, 'array or ArrayAccess');
        }

        if (!(is_array($array) || $array instanceof ArrayAccess)) {
            throw InvalidArgumentException::create(2, 'array or ArrayAccess');
        }

        $constraint = new ArraySubset($subset, $checkForObjectIdentity);

        static::assertThat($array, $constraint, $message);
    }
}
