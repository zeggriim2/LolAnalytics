<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801110627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE historique_league_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE historique_league (id INT NOT NULL, invocateur_id INT DEFAULT NULL, league_id VARCHAR(255) NOT NULL, tier VARCHAR(100) NOT NULL, rank VARCHAR(10) NOT NULL, league_point INT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7F6634421BF8270 ON historique_league (invocateur_id)');
        $this->addSql('COMMENT ON COLUMN historique_league.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE historique_league ADD CONSTRAINT FK_F7F6634421BF8270 FOREIGN KEY (invocateur_id) REFERENCES invocateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE historique_league_id_seq CASCADE');
        $this->addSql('DROP TABLE historique_league');
    }
}
