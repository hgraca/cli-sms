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

namespace Acme\PhpExtension\Test\Uuid;

use Acme\PhpExtension\Test\AbstractUnitTest;
use Acme\PhpExtension\Uuid\Uuid;
use Acme\PhpExtension\Uuid\UuidGenerator;
use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * @small
 */
final class UuidGeneratorUnitTest extends AbstractUnitTest
{
    /**
     * @test
     */
    public function generate(): void
    {
        self::assertTrue(RamseyUuid::isValid((string) UuidGenerator::generate()));
    }

    /**
     * @test
     */
    public static function generateAsString(): void
    {
        self::assertTrue(RamseyUuid::isValid(UuidGenerator::generateAsString()));
    }

    /**
     * @test
     */
    public function override_default_generator_as_uuid(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        UuidGenerator::overrideDefaultGenerator(
            function () use ($uuid) {
                return $uuid;
            }
        );

        self::assertEquals(new Uuid($uuid), UuidGenerator::generate());
    }

    /**
     * @test
     */
    public function override_default_generator_as_string(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        UuidGenerator::overrideDefaultGenerator(
            function () use ($uuid) {
                return $uuid;
            }
        );

        self::assertEquals($uuid, UuidGenerator::generateAsString());
    }

    /**
     * @test
     */
    public function reset(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        UuidGenerator::overrideDefaultGenerator(
            function () use ($uuid) {
                return $uuid;
            }
        );

        self::assertEquals($uuid, (string) UuidGenerator::generate());

        UuidGenerator::reset();
        self::assertNotEquals($uuid, (string) UuidGenerator::generate());
    }
}
