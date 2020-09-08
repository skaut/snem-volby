<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200908180927 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE objection (id INT AUTO_INCREMENT NOT NULL, delegate_id INT NOT NULL, text VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B54481918A0BB485 (delegate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objection ADD CONSTRAINT FK_B54481918A0BB485 FOREIGN KEY (delegate_id) REFERENCES delegate (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE objection');
    }
}
