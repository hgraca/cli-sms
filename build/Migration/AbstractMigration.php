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

namespace Acme\App\Build\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration as DoctrineMigration;

abstract class AbstractMigration extends DoctrineMigration
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    protected function abortIfNotMysql(): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            "Migration can only be executed safely on 'mysql'."
        );
    }
}
