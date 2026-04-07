<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260407225042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE league_entries DROP summoner_id, CHANGE `rank` league_rank VARCHAR(2) DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_league_entry_puuid ON league_entries (puuid)');
        $this->addSql('CREATE INDEX idx_league_entry_lp ON league_entries (league_id, league_points)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_league_entry_puuid ON league_entries');
        $this->addSql('DROP INDEX idx_league_entry_lp ON league_entries');
        $this->addSql('ALTER TABLE league_entries ADD summoner_id VARCHAR(100) NOT NULL, CHANGE league_rank `rank` VARCHAR(2) DEFAULT NULL');
    }
}
