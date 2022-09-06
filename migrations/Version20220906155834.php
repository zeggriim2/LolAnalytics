<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906155834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stat_champion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stat_champion (id INT NOT NULL, hp INT DEFAULT NULL, hpperlevel INT DEFAULT NULL, mp INT DEFAULT NULL, mpperlevel INT DEFAULT NULL, movespeed INT DEFAULT NULL, armor INT DEFAULT NULL, armorperlevel DOUBLE PRECISION DEFAULT NULL, spellblock INT DEFAULT NULL, spellblockperlevel DOUBLE PRECISION DEFAULT NULL, attackrange INT DEFAULT NULL, hpregen DOUBLE PRECISION DEFAULT NULL, hpregenperlevel DOUBLE PRECISION DEFAULT NULL, mpregen INT DEFAULT NULL, mpregenperlevel DOUBLE PRECISION DEFAULT NULL, crit INT DEFAULT NULL, critperlevel INT DEFAULT NULL, attackdamage INT DEFAULT NULL, attackdamageperlevel INT DEFAULT NULL, attackspeedperlevel DOUBLE PRECISION DEFAULT NULL, attackspeed DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN stat_champion.hp IS \'Point de Vie\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.hpperlevel IS \'Point de vie Obtenir par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.mp IS \'Mana\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.mpperlevel IS \'Mana par Niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.movespeed IS \'Vitesse de deplacement\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.armor IS \'Armor\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.armorperlevel IS \'Armor par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.attackrange IS \'Distance d\'\'attaque\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.hpregen IS \'Regen Point de vie\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.hpregenperlevel IS \'Regen Point de vie par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.mpregen IS \'Regen Mana\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.mpregenperlevel IS \'Regen Mana par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.crit IS \'Critique\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.critperlevel IS \'Critique par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.attackdamage IS \'Dommage d\'\'attaque\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.attackdamageperlevel IS \'Dommage d\'\'attaque par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.attackspeedperlevel IS \'Vitesse d\'\'attaque par niveau\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.attackspeed IS \'Vitesse d\'\'attaque\'');
        $this->addSql('COMMENT ON COLUMN stat_champion.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE champion ADD stat_champion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB422BD2846 FOREIGN KEY (stat_champion_id) REFERENCES stat_champion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_45437EB422BD2846 ON champion (stat_champion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB422BD2846');
        $this->addSql('DROP SEQUENCE stat_champion_id_seq CASCADE');
        $this->addSql('DROP TABLE stat_champion');
        $this->addSql('DROP INDEX UNIQ_45437EB422BD2846');
        $this->addSql('ALTER TABLE champion DROP stat_champion_id');
    }
}
