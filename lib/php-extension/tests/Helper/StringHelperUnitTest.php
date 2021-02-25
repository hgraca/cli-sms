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

use Acme\PhpExtension\Helper\StringHelper;
use Acme\PhpExtension\Test\AbstractUnitTest;

/**
 * @small
 */
final class StringHelperUnitTest extends AbstractUnitTest
{
    /**
     * @dataProvider provideStrings
     *
     * @test
     */
    public function contains_finds_needle_when_its_there(string $needle, string $haystack, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, StringHelper::contains($needle, $haystack));
    }

    public function provideStrings(): array
    {
        return [
            ['', 'beginning to ending', true],
            ['beginning', 'beginning to ending', true],
            ['to', 'beginning to ending', true],
            ['ending', 'beginning to ending', true],
            ['unexistent', 'beginning to ending', false],
        ];
    }

    /**
     * @dataProvider provideStudlyTests
     *
     * @test
     */
    public function to_studly_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toStudlyCase($input));
    }

    public function provideStudlyTests(): array
    {
        return [
            ['TABLE_NAME', 'TableName'],
            ['Table_NaMe', 'TableNaMe'],
            ['table_name', 'TableName'],
            ['TableName', 'TableName'],
            ['tableName', 'TableName'],
            ['table-Name', 'TableName'],
            ['table.Name', 'TableName'],
            ['table Name', 'TableName'],
        ];
    }

    /**
     * @dataProvider provideCamelCaseTests
     *
     * @test
     */
    public function to_camel_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toCamelCase($input));
    }

    public function provideCamelCaseTests(): array
    {
        return [
            ['TABLE_NAME', 'tableName'],
            ['Table_NaMe', 'tableNaMe'],
            ['table_name', 'tableName'],
            ['TableName', 'tableName'],
            ['tableName', 'tableName'],
        ];
    }

    /**
     * @dataProvider provideSnakeCaseTests
     *
     * @test
     */
    public function to_snake_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toSnakeCase($input));
    }

    public function provideSnakeCaseTests(): array
    {
        return [
            ['TABLE_NAME', 'table_name'],
            ['Table_NaMe', 'table_na_me'],
            ['TableName', 'table_name'],
            ['table_Name', 'table_name'],
            ['tableName', 'table_name'],
        ];
    }
}
