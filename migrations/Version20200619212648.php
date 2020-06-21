<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200619212648 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE config (item VARCHAR(255) NOT NULL COMMENT \'(DC2Type:string_enum)\', value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(item)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO config (item, value) VALUES (\'voting_begin\', null)');
        $this->addSql('INSERT INTO config (item, value) VALUES (\'voting_end\', null)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE config');
    }
}
