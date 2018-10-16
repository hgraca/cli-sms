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
