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

use Acme\PhpExtension\Identity\AbstractUuidId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidBinaryType;
use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * This type mapper stores the UUIDs in binary format, in the DB.
 * This makes it faster to query on the IDs and they take less space (they only take 16 characters as opposed to the
 * UUID 36 characters string).
 * However, if you look at the UUID stored in the DB, you won't be able to see the actual UUID, only its byte string.
 */
abstract class AbstractBinaryUuidType extends UuidBinaryType
{
    use TypeTrait;

    /**
     * @throws \Doctrine\DBAL\Types\ConversionException
     *
     * @return AbstractUuidId|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        /** @var RamseyUuid $ramseyUuid */
        $ramseyUuid = parent::convertToPHPValue($value, $platform);

        if ($ramseyUuid === null) {
            return null;
        }

        return $this->createSpecificObject((string) $ramseyUuid);
    }

    /**
     * @param AbstractUuidId $value
     *
     * @throws \Doctrine\DBAL\Types\ConversionException
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $uuidString = (string) $value;

        return parent::convertToDatabaseValue($uuidString, $platform);
    }
}
