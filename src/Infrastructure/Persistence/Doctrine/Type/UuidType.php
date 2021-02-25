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

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\PhpExtension\Uuid\Uuid;

/**
 * This class is here just as an example of how to implement a mapper for a type that is not an ID.
 */
final class UuidType extends AbstractUuidType
{
    protected function getMappedClass(): string
    {
        return Uuid::class;
    }
}
