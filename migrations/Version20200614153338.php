<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200614153338 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9599CB6A217BBB47 ON users_vote (person_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_9599CB6A217BBB47 ON users_vote');
    }
}
