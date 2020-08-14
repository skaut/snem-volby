<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200814202227 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE commission_member (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, UNIQUE INDEX UNIQ_19AC7994217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE commission_member');
    }
}
