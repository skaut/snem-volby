<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200619185304 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql(<<<EOT
CREATE TABLE delegate (
    id INT AUTO_INCREMENT NOT NULL,
    person_id INT NOT NULL,
    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    voted_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    UNIQUE INDEX UNIQ_5F8662FE217BBB47 (person_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
EOT);
        $this->addSql('DROP TABLE users_vote');
        $this->addSql('ALTER TABLE vote CHANGE choice choice VARCHAR(255) NOT NULL COMMENT \'(DC2Type:string_enum)\'');
        $this->addSql('ALTER TABLE vote DROP created_at, CHANGE choice choice VARCHAR(255) NOT NULL COMMENT \'(DC2Type:string_enum)\'');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql(<<<EOT
CREATE TABLE users_vote (
    id INT AUTO_INCREMENT NOT NULL,
    person_id INT NOT NULL,
    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    UNIQUE INDEX UNIQ_9599CB6A217BBB47 (person_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = '' 
EOT
        );
        $this->addSql('DROP TABLE delegate');
        $this->addSql('ALTER TABLE vote CHANGE choice choice VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql(<<<EOT
ALTER TABLE vote 
    ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    CHANGE choice choice VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`
EOT);
    }
}
