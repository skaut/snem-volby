<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200719160409 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql(<<<EOF
CREATE TABLE candidate_function (
    id INT NOT NULL,
    label VARCHAR(64) NOT NULL,
    max_count INT DEFAULT NULL,
    `show` TINYINT(1) NOT NULL,
    `order` SMALLINT NOT NULL,
    UNIQUE INDEX UNIQ_6BF054EEEA750E8 (label),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
EOF);

        $this->addSql(<<<EOF
CREATE TABLE candidate (
    id INT NOT NULL,
    function_id INT NOT NULL,
    running_mate INT DEFAULT NULL,
    person_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    INDEX IDX_C8B28E4467048801 (function_id),
    UNIQUE INDEX UNIQ_C8B28E442A13057C (running_mate),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
EOF);

        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E4467048801 FOREIGN KEY (function_id) REFERENCES candidate_function (id)');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44A0B9DAAC FOREIGN KEY (running_mate) REFERENCES candidate (id)');

        $this->addSql(<<<EOF
INSERT INTO `candidate_function` (`id`, `label`, `max_count`, `show`, `order`) VALUES
(10,	'náčelník',	1,	1,	10),
(11,	'náčelní',	1,	1,	20),
(13,	'místonáčelník',	1,	0,	100),
(14,	'místonáčelní',	1,	0,	100),
(23,	'ÚRKJ',	7,	1,	40),
(25,	'člen RSRJ',	5,	1,	50),
(106,	'volený člen Náčelnictva',	10,	1,	30);
EOF
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E4467048801');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44A0B9DAAC');
        $this->addSql('DROP TABLE candidate_function');
        $this->addSql('DROP TABLE candidate');
    }
}
