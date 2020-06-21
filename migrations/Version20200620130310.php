<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200620130310 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO config (item, value) VALUES (\'voting_publish\', null)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM config WHERE item = \'voting_publish\'');
    }
}
