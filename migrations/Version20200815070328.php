<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200815070328 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE delegate ADD name VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD unit_number VARCHAR(255) DEFAULT NULL, ADD unit_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE delegate DROP name, DROP type, DROP unit_number, DROP unit_name');
    }
}
