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

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\ClassHelper;
use Acme\PhpExtension\Test\AbstractUnitTest;

/**
 * @small
 */
final class ClassHelperUnitTest extends AbstractUnitTest
{
    /**
     * @test
     */
    public function extract_canonical_class_name(): void
    {
        self::assertSame('ClassHelperUnitTest', ClassHelper::extractCanonicalClassName(__CLASS__));
    }

    /**
     * @test
     */
    public function extract_canonical_method_name(): void
    {
        self::assertSame(
            'extract_canonical_method_name',
            ClassHelper::extractCanonicalMethodName(__METHOD__)
        );
    }
}
