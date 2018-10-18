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

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210304172208 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE User ADD COLUMN message_count INTEGER UNSIGNED DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_2DA17977F85E0677');
        $this->addSql('DROP INDEX UNIQ_2DA17977E7927C74');
        $this->addSql('DROP INDEX UNIQ_2DA179773C7323E0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__User AS SELECT id, full_name, username, email, mobile, password, roles FROM User');
        $this->addSql('DROP TABLE User');
        $this->addSql('CREATE TABLE User (id CHAR(36) NOT NULL --(DC2Type:user_id)
        , full_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO User (id, full_name, username, email, mobile, password, roles) SELECT id, full_name, username, email, mobile, password, roles FROM __temp__User');
        $this->addSql('DROP TABLE __temp__User');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA17977F85E0677 ON User (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA17977E7927C74 ON User (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA179773C7323E0 ON User (mobile)');
    }
}
