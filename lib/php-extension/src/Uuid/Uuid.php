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

namespace Acme\PhpExtension\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid)
    {
        if (!self::isValid($uuid)) {
            throw new InvalidUuidStringException($uuid);
        }

        $this->uuid = $uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public static function isValid(string $uuid): bool
    {
        return RamseyUuid::isValid($uuid);
    }
}
