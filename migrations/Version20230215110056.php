<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215110056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stat (id INT NOT NULL, hp DOUBLE PRECISION NOT NULL, hpperlevel INT NOT NULL, mp DOUBLE PRECISION NOT NULL, mpperlevel DOUBLE PRECISION NOT NULL, movespeed INT NOT NULL, armor INT NOT NULL, armorperlevel DOUBLE PRECISION NOT NULL, spellblock DOUBLE PRECISION NOT NULL, spellblockperlevel DOUBLE PRECISION NOT NULL, attackrange INT NOT NULL, hpregen DOUBLE PRECISION NOT NULL, hpregenperlevel DOUBLE PRECISION NOT NULL, mpregen DOUBLE PRECISION NOT NULL, mpregenperlevel DOUBLE PRECISION NOT NULL, crit INT NOT NULL, critperlevel INT NOT NULL, attackdamage INT NOT NULL, attackdamageperlevel DOUBLE PRECISION NOT NULL, attackspeedperlevel DOUBLE PRECISION NOT NULL, attackspeed DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE champion ADD stat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB49502F0B FOREIGN KEY (stat_id) REFERENCES stat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_45437EB49502F0B ON champion (stat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB49502F0B');
        $this->addSql('DROP SEQUENCE stat_id_seq CASCADE');
        $this->addSql('DROP TABLE stat');
        $this->addSql('DROP INDEX IDX_45437EB49502F0B');
        $this->addSql('ALTER TABLE champion DROP stat_id');
    }
}
