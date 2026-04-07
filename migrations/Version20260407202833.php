<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260407202833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE league_entries (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, puuid VARCHAR(78) NOT NULL, summoner_id VARCHAR(100) NOT NULL, league_points INT NOT NULL, wins INT NOT NULL, losses INT NOT NULL, `rank` VARCHAR(2) DEFAULT NULL, hot_streak TINYINT(1) NOT NULL, veteran TINYINT(1) NOT NULL, fresh_blood TINYINT(1) NOT NULL, INDEX IDX_9451AAB458AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leagues (id INT AUTO_INCREMENT NOT NULL, tier VARCHAR(20) NOT NULL, queue VARCHAR(30) NOT NULL, platform VARCHAR(10) NOT NULL, total_lp INT NOT NULL, last_refreshed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX league_tier_queue_platform_unique (tier, queue, platform), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE league_entries ADD CONSTRAINT FK_9451AAB458AFC4DE FOREIGN KEY (league_id) REFERENCES leagues (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE league_entries DROP FOREIGN KEY FK_9451AAB458AFC4DE');
        $this->addSql('DROP TABLE league_entries');
        $this->addSql('DROP TABLE leagues');
    }
}
