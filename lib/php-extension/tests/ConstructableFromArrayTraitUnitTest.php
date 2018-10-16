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

namespace Acme\PhpExtension\Test;

/**
 * @small
 */
final class ConstructableFromArrayTraitUnitTest extends AbstractUnitTest
{
    /**
     * @test
     *
     * @throws \ReflectionException
     */
    public function fromArray(): void
    {
        $value = 123;

        $dto = DummyConstructableFromArray::fromArray(['prop1' => $value, 'inexistent' => 'foo']);

        self::assertInstanceOf(DummyConstructableFromArray::class, $dto);
        self::assertSame($value, $dto->getProp1());
        self::assertSame(0, $dto->getProp2());
    }

    /**
     * @test
     * @expectedException \Exception
     *
     * @throws \ReflectionException
     */
    public function fromArray_ThrowsExceptionIfArgumentIsMissing(): void
    {
        $value = 123;

        $dto = DummyConstructableFromArray::fromArray(['inexistent' => 'foo']);

        self::assertInstanceOf(DummyConstructableFromArray::class, $dto);
        self::assertSame($value, $dto->getProp1());
    }
}
