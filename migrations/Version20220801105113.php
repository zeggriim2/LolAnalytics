<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801105113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE baron_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dragon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE inhibitor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rencontre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rift_herald_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tower_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE baron (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE dragon (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE inhibitor (id INT NOT NULL, first BOOLEAN NOT NULL, kills INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rencontre (id INT NOT NULL, map_id INT DEFAULT NULL, game_id BIGINT NOT NULL, game_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, game_duration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_460C35ED53C55F64 ON rencontre (map_id)');
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
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED53C55F64 FOREIGN KEY (map_id) REFERENCES map (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rencontre_invocateur ADD CONSTRAINT FK_29DE56916CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rencontre_invocateur ADD CONSTRAINT FK_29DE569121BF8270 FOREIGN KEY (invocateur_id) REFERENCES invocateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FC55CEFBA FOREIGN KEY (baron_id) REFERENCES baron (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F685856D6 FOREIGN KEY (dragon_id) REFERENCES dragon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA17B2B3D FOREIGN KEY (inhibitor_id) REFERENCES inhibitor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F243E9D61 FOREIGN KEY (rift_herald_id) REFERENCES rift_herald (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F2F05CD7E FOREIGN KEY (tower_id) REFERENCES tower (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F6CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE map RENAME COLUMN map_id TO map_id_lol');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FC55CEFBA');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F685856D6');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61FA17B2B3D');
        $this->addSql('ALTER TABLE rencontre_invocateur DROP CONSTRAINT FK_29DE56916CFC0818');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F6CFC0818');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F243E9D61');
        $this->addSql('ALTER TABLE team DROP CONSTRAINT FK_C4E0A61F2F05CD7E');
        $this->addSql('DROP SEQUENCE baron_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dragon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE inhibitor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rencontre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rift_herald_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tower_id_seq CASCADE');
        $this->addSql('DROP TABLE baron');
        $this->addSql('DROP TABLE dragon');
        $this->addSql('DROP TABLE inhibitor');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE rencontre_invocateur');
        $this->addSql('DROP TABLE rift_herald');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tower');
        $this->addSql('ALTER TABLE map RENAME COLUMN map_id_lol TO map_id');
    }
}
