<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251007084016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE participants (id INT AUTO_INCREMENT NOT NULL, match_id INT DEFAULT NULL, puuid VARCHAR(255) NOT NULL, summoner_id VARCHAR(255) NOT NULL, champion_id INT NOT NULL, kills INT NOT NULL, deaths INT NOT NULL, assists INT NOT NULL, win TINYINT(1) NOT NULL, INDEX IDX_716970922ABEACD6 (match_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_716970922ABEACD6 FOREIGN KEY (match_id) REFERENCES matches (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matches DROP participants');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_716970922ABEACD6');
        $this->addSql('DROP TABLE participants');
        $this->addSql('ALTER TABLE matches ADD participants JSON NOT NULL');
    }
}
