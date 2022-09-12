<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912124628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE competition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE equipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE versus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE competition (id INT NOT NULL, name VARCHAR(255) NOT NULL, annee DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN competition.annee IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN competition.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE competition_equipe (competition_id INT NOT NULL, equipe_id INT NOT NULL, PRIMARY KEY(competition_id, equipe_id))');
        $this->addSql('CREATE INDEX IDX_4B0A7AC67B39D312 ON competition_equipe (competition_id)');
        $this->addSql('CREATE INDEX IDX_4B0A7AC66D861B89 ON competition_equipe (equipe_id)');
        $this->addSql('CREATE TABLE equipe (id INT NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(150) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN equipe.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE versus (id INT NOT NULL, equipe1_id INT DEFAULT NULL, equipe2_id INT DEFAULT NULL, date_versus TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, score_equipe1 INT NOT NULL, score_equipe2 INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_31AEA4694265900C ON versus (equipe1_id)');
        $this->addSql('CREATE INDEX IDX_31AEA46950D03FE2 ON versus (equipe2_id)');
        $this->addSql('COMMENT ON COLUMN versus.date_versus IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN versus.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE competition_equipe ADD CONSTRAINT FK_4B0A7AC67B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE competition_equipe ADD CONSTRAINT FK_4B0A7AC66D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE versus ADD CONSTRAINT FK_31AEA4694265900C FOREIGN KEY (equipe1_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE versus ADD CONSTRAINT FK_31AEA46950D03FE2 FOREIGN KEY (equipe2_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE competition_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE equipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE versus_id_seq CASCADE');
        $this->addSql('ALTER TABLE competition_equipe DROP CONSTRAINT FK_4B0A7AC67B39D312');
        $this->addSql('ALTER TABLE competition_equipe DROP CONSTRAINT FK_4B0A7AC66D861B89');
        $this->addSql('ALTER TABLE versus DROP CONSTRAINT FK_31AEA4694265900C');
        $this->addSql('ALTER TABLE versus DROP CONSTRAINT FK_31AEA46950D03FE2');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE competition_equipe');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE versus');
    }
}
