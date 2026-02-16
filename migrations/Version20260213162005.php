<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213162005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE champion_images (id INT AUTO_INCREMENT NOT NULL, riot_id VARCHAR(100) DEFAULT NULL, version VARCHAR(100) DEFAULT NULL, `full` VARCHAR(255) NOT NULL, sprite VARCHAR(255) NOT NULL, `group` VARCHAR(100) NOT NULL, x INT NOT NULL, y INT NOT NULL, w INT NOT NULL, h INT NOT NULL, INDEX IDX_4FA7D47DAF5F8B9BBF1CD3C3 (riot_id, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion_info (id INT AUTO_INCREMENT NOT NULL, riot_id VARCHAR(100) DEFAULT NULL, version VARCHAR(100) DEFAULT NULL, attack INT NOT NULL, defense INT NOT NULL, magic INT NOT NULL, difficulty INT NOT NULL, INDEX IDX_7D9B0EEAF5F8B9BBF1CD3C3 (riot_id, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champion_stats (id INT AUTO_INCREMENT NOT NULL, riot_id VARCHAR(100) DEFAULT NULL, version VARCHAR(100) DEFAULT NULL, hp DOUBLE PRECISION NOT NULL, hp_per_level DOUBLE PRECISION NOT NULL, mp DOUBLE PRECISION NOT NULL, mp_per_level DOUBLE PRECISION NOT NULL, move_speed DOUBLE PRECISION NOT NULL, armor DOUBLE PRECISION NOT NULL, armor_per_level DOUBLE PRECISION NOT NULL, spell_block DOUBLE PRECISION NOT NULL, spell_block_per_level DOUBLE PRECISION NOT NULL, attack_range DOUBLE PRECISION NOT NULL, hp_regen DOUBLE PRECISION NOT NULL, hp_regen_per_level DOUBLE PRECISION NOT NULL, mp_regen DOUBLE PRECISION NOT NULL, mp_regen_per_level DOUBLE PRECISION NOT NULL, crit DOUBLE PRECISION NOT NULL, crit_per_level DOUBLE PRECISION NOT NULL, attack_damage DOUBLE PRECISION NOT NULL, attack_damage_per_level DOUBLE PRECISION NOT NULL, attack_speed DOUBLE PRECISION NOT NULL, attack_speed_per_level DOUBLE PRECISION NOT NULL, INDEX IDX_E5363C03AF5F8B9BBF1CD3C3 (riot_id, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champions (riot_id VARCHAR(100) NOT NULL, version VARCHAR(100) NOT NULL, champion_key VARCHAR(10) NOT NULL, name VARCHAR(100) NOT NULL, title VARCHAR(255) NOT NULL, blurb LONGTEXT NOT NULL, partype VARCHAR(100) NOT NULL, tags JSON NOT NULL, INDEX IDX_D747FBE0BF1CD3C3 (version), PRIMARY KEY(riot_id, version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE champion_images ADD CONSTRAINT FK_4FA7D47DAF5F8B9BBF1CD3C3 FOREIGN KEY (riot_id, version) REFERENCES champions (riot_id, version)');
        $this->addSql('ALTER TABLE champion_info ADD CONSTRAINT FK_7D9B0EEAF5F8B9BBF1CD3C3 FOREIGN KEY (riot_id, version) REFERENCES champions (riot_id, version)');
        $this->addSql('ALTER TABLE champion_stats ADD CONSTRAINT FK_E5363C03AF5F8B9BBF1CD3C3 FOREIGN KEY (riot_id, version) REFERENCES champions (riot_id, version)');
        $this->addSql('ALTER TABLE champions ADD CONSTRAINT FK_D747FBE0BF1CD3C3 FOREIGN KEY (version) REFERENCES game_data_versions (version)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE champion_images DROP FOREIGN KEY FK_4FA7D47DAF5F8B9BBF1CD3C3');
        $this->addSql('ALTER TABLE champion_info DROP FOREIGN KEY FK_7D9B0EEAF5F8B9BBF1CD3C3');
        $this->addSql('ALTER TABLE champion_stats DROP FOREIGN KEY FK_E5363C03AF5F8B9BBF1CD3C3');
        $this->addSql('ALTER TABLE champions DROP FOREIGN KEY FK_D747FBE0BF1CD3C3');
        $this->addSql('DROP TABLE champion_images');
        $this->addSql('DROP TABLE champion_info');
        $this->addSql('DROP TABLE champion_stats');
        $this->addSql('DROP TABLE champions');
    }
}
