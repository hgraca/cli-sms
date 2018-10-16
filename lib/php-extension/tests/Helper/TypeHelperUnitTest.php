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

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\TypeHelper;
use Acme\PhpExtension\Test\AbstractUnitTest;

/**
 * @small
 */
final class TypeHelperUnitTest extends AbstractUnitTest
{
    /**
     * @test
     * @dataProvider provideValues
     */
    public function getType($value, string $expectedType): void
    {
        self::assertEquals($expectedType, TypeHelper::getType($value));
    }

    public function provideValues(): array
    {
        return [
            [true, 'boolean'],
            [1, 'integer'],
            [1.2, 'double'],
            ['', 'string'],
            [[], '[]'],
            [[1, 2], 'integer[]'],
            [[[1], [2]], 'integer[][]'],
            [null, 'NULL'],
            [$this, self::class],
        ];
    }
}
