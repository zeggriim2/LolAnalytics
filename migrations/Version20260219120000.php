<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260219120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add champion_skins table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE champion_skins (id INT AUTO_INCREMENT NOT NULL, riot_id VARCHAR(100) DEFAULT NULL, version VARCHAR(100) DEFAULT NULL, skin_id VARCHAR(100) NOT NULL, num INT NOT NULL, name VARCHAR(255) NOT NULL, chromas TINYINT(1) NOT NULL, INDEX IDX_champion_skins_champion (riot_id, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE champion_skins ADD CONSTRAINT FK_champion_skins_champion FOREIGN KEY (riot_id, version) REFERENCES champions (riot_id, version)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE champion_skins DROP FOREIGN KEY FK_champion_skins_champion');
        $this->addSql('DROP TABLE champion_skins');
    }
}
