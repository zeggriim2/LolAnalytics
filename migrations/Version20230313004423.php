<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313004423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE division_league_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE league_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE platform_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE queue_league_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE summoner_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tier_league_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE division_league (id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C492BA9F77153098 ON division_league (code)');
        $this->addSql('CREATE TABLE league (id INT NOT NULL, division_league_id INT DEFAULT NULL, tier_league_id INT DEFAULT NULL, queue_league_id INT DEFAULT NULL, summoner_id INT DEFAULT NULL, league_id VARCHAR(125) NOT NULL, league_points INT NOT NULL, wins INT NOT NULL, looses INT NOT NULL, veteran BOOLEAN NOT NULL, inactive BOOLEAN NOT NULL, fresh_blood BOOLEAN NOT NULL, hot_streak BOOLEAN NOT NULL, actif BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EB4C31890258E63 ON league (division_league_id)');
        $this->addSql('CREATE INDEX IDX_3EB4C3184B1868B0 ON league (tier_league_id)');
        $this->addSql('CREATE INDEX IDX_3EB4C3187EB777ED ON league (queue_league_id)');
        $this->addSql('CREATE INDEX IDX_3EB4C318BC01C675 ON league (summoner_id)');
        $this->addSql('CREATE TABLE platform (id INT NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3952D0CB77153098 ON platform (code)');
        $this->addSql('CREATE TABLE queue_league (id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_455E9D177153098 ON queue_league (code)');
        $this->addSql('CREATE TABLE summoner (id INT NOT NULL, account_id VARCHAR(56) NOT NULL, profile_icon_id INT NOT NULL, name VARCHAR(255) NOT NULL, id_lol VARCHAR(63) NOT NULL, puuid VARCHAR(78) NOT NULL, sumoner_level INT NOT NULL, created_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN summoner.created_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN summoner.updated_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tier_league (id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C31890258E63 FOREIGN KEY (division_league_id) REFERENCES division_league (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C3184B1868B0 FOREIGN KEY (tier_league_id) REFERENCES tier_league (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C3187EB777ED FOREIGN KEY (queue_league_id) REFERENCES queue_league (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318BC01C675 FOREIGN KEY (summoner_id) REFERENCES summoner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE division_league_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE league_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE platform_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE queue_league_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE summoner_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tier_league_id_seq CASCADE');
        $this->addSql('ALTER TABLE league DROP CONSTRAINT FK_3EB4C31890258E63');
        $this->addSql('ALTER TABLE league DROP CONSTRAINT FK_3EB4C3184B1868B0');
        $this->addSql('ALTER TABLE league DROP CONSTRAINT FK_3EB4C3187EB777ED');
        $this->addSql('ALTER TABLE league DROP CONSTRAINT FK_3EB4C318BC01C675');
        $this->addSql('DROP TABLE division_league');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE queue_league');
        $this->addSql('DROP TABLE summoner');
        $this->addSql('DROP TABLE tier_league');
    }
}
