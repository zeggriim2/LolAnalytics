<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905221102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE baron_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE champion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dragon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE historique_league_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE inhibitor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invocateur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE language_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE map_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rencontre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rift_herald_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tower_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE version_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE baron (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE champion (id INT NOT NULL, version_id INT DEFAULT NULL, image_id INT DEFAULT NULL, id_name VARCHAR(100) NOT NULL, key VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, partype VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_45437EB44BBC2705 ON champion (version_id)');
        $this->addSql('CREATE INDEX IDX_45437EB43DA5256D ON champion (image_id)');
        $this->addSql('COMMENT ON COLUMN champion.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE dragon (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE historique_league (id INT NOT NULL, invocateur_id INT DEFAULT NULL, league_id VARCHAR(255) NOT NULL, tier VARCHAR(100) NOT NULL, rank VARCHAR(10) NOT NULL, league_point INT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, wins INT NOT NULL, losses INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7F6634421BF8270 ON historique_league (invocateur_id)');
        $this->addSql('COMMENT ON COLUMN historique_league.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, complete VARCHAR(150) NOT NULL, sprite VARCHAR(150) NOT NULL, groupe VARCHAR(100) NOT NULL, x INT NOT NULL, y INT NOT NULL, w INT NOT NULL, h INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN image.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE inhibitor (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invocateur (id INT NOT NULL, puuid VARCHAR(255) NOT NULL, summoner_level INT NOT NULL, name VARCHAR(255) NOT NULL, id_lol VARCHAR(255) NOT NULL, profile_icon_id INT NOT NULL, accound_id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2128394BCFCB9868 ON invocateur (puuid)');
        $this->addSql('COMMENT ON COLUMN invocateur.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN invocateur.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE language (id INT NOT NULL, code VARCHAR(20) NOT NULL, language VARCHAR(150) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN language.id IS \'ID interne\'');
        $this->addSql('COMMENT ON COLUMN language.code IS \'Code langue\'');
        $this->addSql('COMMENT ON COLUMN language.language IS \'Langue complète\'');
        $this->addSql('COMMENT ON COLUMN language.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE map (id INT NOT NULL, map_id_lol INT NOT NULL, name VARCHAR(255) NOT NULL, notes VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93ADAABBC2CD822B ON map (map_id_lol)');
        $this->addSql('COMMENT ON COLUMN map.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE rencontre (id INT NOT NULL, map_id INT DEFAULT NULL, game_id BIGINT NOT NULL, game_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, game_duration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_460C35ED53C55F64 ON rencontre (map_id)');
        $this->addSql('COMMENT ON COLUMN rencontre.game_creation IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rencontre.game_duration IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE rencontre_invocateur (rencontre_id INT NOT NULL, invocateur_id INT NOT NULL, PRIMARY KEY(rencontre_id, invocateur_id))');
        $this->addSql('CREATE INDEX IDX_29DE56916CFC0818 ON rencontre_invocateur (rencontre_id)');
        $this->addSql('CREATE INDEX IDX_29DE569121BF8270 ON rencontre_invocateur (invocateur_id)');
        $this->addSql('CREATE TABLE rift_herald (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE team (id INT NOT NULL, baron_id INT DEFAULT NULL, dragon_id INT DEFAULT NULL, inhibitor_id INT DEFAULT NULL, rift_herald_id INT DEFAULT NULL, tower_id INT DEFAULT NULL, rencontre_id INT DEFAULT NULL, team_id_lol INT NOT NULL, win BOOLEAN NOT NULL, ban1_champion_id INT NOT NULL, ban2_champion_id INT NOT NULL, ban3_champion_id INT NOT NULL, ban4_champion_id INT NOT NULL, ban5_champion_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61FC55CEFBA ON team (baron_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F685856D6 ON team (dragon_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61FA17B2B3D ON team (inhibitor_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F243E9D61 ON team (rift_herald_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F2F05CD7E ON team (tower_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F6CFC0818 ON team (rencontre_id)');
        $this->addSql('CREATE TABLE tower (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE version (id INT NOT NULL, name VARCHAR(20) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN version.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB44BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB43DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE historique_league ADD CONSTRAINT FK_F7F6634421BF8270 FOREIGN KEY (invocateur_id) REFERENCES invocateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED53C55F64 FOREIGN KEY (map_id) REFERENCES map (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rencontre_invocateur ADD CONSTRAINT FK_29DE56916CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rencontre_invocateur ADD CONSTRAINT FK_29DE569121BF8270 FOREIGN KEY (invocateur_id) REFERENCES invocateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FC55CEFBA FOREIGN KEY (baron_id) REFERENCES baron (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F685856D6 FOREIGN KEY (dragon_id) REFERENCES dragon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA17B2B3D FOREIGN KEY (inhibitor_id) REFERENCES inhibitor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F243E9D61 FOREIGN KEY (rift_herald_id) REFERENCES rift_herald (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F2F05CD7E FOREIGN KEY (tower_id) REFERENCES tower (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F6CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE baron_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE champion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dragon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE historique_league_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE inhibitor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invocateur_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE language_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE map_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rencontre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rift_herald_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tower_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE version_id_seq CASCADE');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB44BBC2705');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB43DA5256D');
        $this->addSql('ALTER TABLE historique_league DROP CONSTRAINT FK_F7F6634421BF8270');
        $this->addSql('ALTER TABLE rencontre DROP CONSTRAINT FK_460C35ED53C55F64');
        $this->addSql('ALTER TABLE rencontre_invocateur DROP CONSTRAINT FK_29DE56916CFC0818');
        $this->addSql('ALTER TABLE rencontre_invocateur DROP CONSTRAINT FK_29DE569121BF8270');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FC55CEFBA');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F685856D6');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FA17B2B3D');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F243E9D61');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F2F05CD7E');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F6CFC0818');
        $this->addSql('DROP TABLE baron');
        $this->addSql('DROP TABLE champion');
        $this->addSql('DROP TABLE dragon');
        $this->addSql('DROP TABLE historique_league');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE inhibitor');
        $this->addSql('DROP TABLE invocateur');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE map');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE rencontre_invocateur');
        $this->addSql('DROP TABLE rift_herald');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tower');
        $this->addSql('DROP TABLE version');
    }
}
