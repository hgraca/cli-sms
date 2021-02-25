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

namespace Acme\PhpExtension\Test\DateTime;

use Acme\PhpExtension\DateTime\DateTimeGenerator;
use Acme\PhpExtension\Test\AbstractUnitTest;
use DateTimeImmutable;
use DateTimeZone;

/**
 * @small
 */
final class DateTimeGeneratorUnitTest extends AbstractUnitTest
{
    const TOLERATED_SECONDS_DIFF = 5;

    /**
     * @test
     */
    public function generate_now(): void
    {
        self::assertLessThan(
            \time() + self::TOLERATED_SECONDS_DIFF,
            DateTimeGenerator::generate()->getTimestamp()
        );
    }

    /**
     * @dataProvider provideDateTime
     *
     * @test
     */
    public function generate(
        string $time,
        ?DateTimeZone $timezone,
        int $expectedTimestamp
    ): void {
        self::assertEquals($expectedTimestamp, DateTimeGenerator::generate($time, $timezone)->getTimestamp());
    }

    public function provideDateTime(): array
    {
        return [
            ['Sun, 22 Apr 2018 19:21:32 GMT', null, 1524424892],
            ['Sunday, 22 April 2018 19:21:32', new DateTimeZone('Europe/Amsterdam'), 1524417692],
        ];
    }

    /**
     * @throws \Exception
     *
     * @test
     */
    public function override_default_generator(): void
    {
        $date = '2018-10-21';
        DateTimeGenerator::overrideDefaultGenerator(
            function () use ($date) {
                return new DateTimeImmutable($date);
            }
        );

        self::assertEquals(new DateTimeImmutable($date), DateTimeGenerator::generate('abc'));
    }

    /**
     * @throws \Exception
     *
     * @test
     */
    public function reset(): void
    {
        $date = '2018-10-21';
        DateTimeGenerator::overrideDefaultGenerator(
            function () use ($date) {
                return new DateTimeImmutable($date);
            }
        );
        self::assertEquals(new DateTimeImmutable($date), DateTimeGenerator::generate('abc'));

        DateTimeGenerator::reset();
        self::assertLessThan(
            \time() + self::TOLERATED_SECONDS_DIFF,
            DateTimeGenerator::generate()->getTimestamp()
        );
    }
}
