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

namespace Acme\PhpExtension\Test\Exception;

use Acme\PhpExtension\Exception\AcmeExceptionInterface;
use Acme\PhpExtension\Exception\AcmeExceptionTrait;
use Acme\PhpExtension\Test\AbstractUnitTest;
use Exception;

/**
 * @small
 */
final class AcmeExceptionTraitUnitTest extends AbstractUnitTest
{
    /**
     * @test
     */
    public function construct_without_arguments(): void
    {
        $exception = new DummyException();

        self::assertSame('DummyException', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertNull($exception->getPrevious());
    }

    /**
     * @test
     */
    public function construct_with_arguments(): void
    {
        $message = 'some_message';
        $code = 666;
        $previous = new Exception();

        $exception = new class($message, $code, $previous) extends Exception implements AcmeExceptionInterface {
            use AcmeExceptionTrait;
        };

        self::assertSame($message, $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
