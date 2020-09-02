<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200822170343 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vote ADD candidate_id INT NOT NULL, DROP choice');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856491BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('CREATE INDEX IDX_5A10856491BD8781 ON vote (candidate_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856491BD8781');
        $this->addSql('DROP INDEX IDX_5A10856491BD8781 ON vote');
        $this->addSql('ALTER TABLE vote ADD choice VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:string_enum)\', DROP candidate_id');
    }
}
