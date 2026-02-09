<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260206170219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add summoner and link with participants';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE summoners (puuid VARCHAR(78) NOT NULL, game_name VARCHAR(100) NOT NULL, tag_line VARCHAR(10) NOT NULL, profile_icon_id INT NOT NULL, summoner_level INT NOT NULL, platform VARCHAR(10) NOT NULL, last_updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(puuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participants ADD summoner_puuid VARCHAR(78) DEFAULT NULL');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_716970926E532956 FOREIGN KEY (summoner_puuid) REFERENCES summoners (puuid) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_716970926E532956 ON participants (summoner_puuid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE summoners');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_716970926E532956');
        $this->addSql('DROP INDEX IDX_716970926E532956 ON participants');
        $this->addSql('ALTER TABLE participants DROP summoner_puuid');
    }
}
