<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200718202304 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vote CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:vote_id)\'');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vote CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
