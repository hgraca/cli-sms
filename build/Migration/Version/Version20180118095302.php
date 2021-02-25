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

namespace Acme\App\Build\Migration\Version;

use Acme\App\Build\Migration\ConfigurationAwareMigration;
use Acme\App\Build\Migration\MigrationHelperTrait;
use Doctrine\DBAL\Schema\Schema;

final class Version20180118095302 extends ConfigurationAwareMigration
{
    use MigrationHelperTrait;

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\Migrations\Exception\AbortMigration
     */
    public function up(Schema $schema): void
    {
        $engine = $this->connection->getDatabasePlatform()->getName();
        switch ($engine) {
            case 'mysql':
                $this->addSql(
                    'CREATE TABLE KeyValueStorage (`key` VARCHAR(255) PRIMARY KEY, `value` VARCHAR(255) DEFAULT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
                );
                break;
            case 'sqlite':
                $this->addSql('CREATE TABLE KeyValueStorage (`key` TEXT PRIMARY KEY, `value` TEXT DEFAULT NULL)');
                break;
            default:
                $this->abortIf(true, "Unknown engine type '$engine'.");
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE KeyValueStorage');
    }
}
