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

namespace Acme\App\Build\Migration;

use Doctrine\DBAL\DBALException;
use Doctrine\Migrations\AbstractMigration as DoctrineMigration;
use Doctrine\Migrations\Exception\AbortMigration;

abstract class AbstractMigration extends DoctrineMigration
{
    /**
     * @throws DBALException
     * @throws AbortMigration
     */
    protected function abortIfNotMysql(): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            "Migration can only be executed safely on 'mysql'."
        );
    }
}
