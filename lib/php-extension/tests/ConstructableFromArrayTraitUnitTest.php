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

use Exception;

/**
 * @small
 */
final class ConstructableFromArrayTraitUnitTest extends AbstractUnitTest
{
    /**
     * @throws \ReflectionException
     *
     * @test
     */
    public function from_array(): void
    {
        $value = 123;

        $dto = DummyConstructableFromArray::fromArray(['prop1' => $value, 'inexistent' => 'foo']);

        self::assertInstanceOf(DummyConstructableFromArray::class, $dto);
        self::assertSame($value, $dto->getProp1());
        self::assertSame(0, $dto->getProp2());
    }

    /**
     * @throws \ReflectionException
     *
     * @test
     */
    public function from_array_throws_exception_if_argument_is_missing(): void
    {
        $value = 123;
        $this->expectException(Exception::class);
        $dto = DummyConstructableFromArray::fromArray(['inexistent' => 'foo']);

        self::assertInstanceOf(DummyConstructableFromArray::class, $dto);
        self::assertSame($value, $dto->getProp1());
    }
}
