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

namespace Acme\PhpExtension\Test\Exception;

use Acme\PhpExtension\Exception\AcmeRuntimeException;
use Acme\PhpExtension\Test\AbstractUnitTest;

/**
 * @small
 */
final class AcmeRuntimeExceptionUnitTest extends AbstractUnitTest
{
    /**
     * @test
     */
    public function construct_without_arguments(): void
    {
        $exception = new AcmeRuntimeException();

        self::assertSame('AcmeRuntimeException', $exception->getMessage());
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
        $previous = new AcmeRuntimeException();

        $exception = new AcmeRuntimeException($message, $code, $previous);

        self::assertSame($message, $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
