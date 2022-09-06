<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906072709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE info_champion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE info_champion (id INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, magic INT NOT NULL, difficulty INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN info_champion.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE champion ADD info_champion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB42547FD14 FOREIGN KEY (info_champion_id) REFERENCES info_champion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_45437EB42547FD14 ON champion (info_champion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB42547FD14');
        $this->addSql('DROP SEQUENCE info_champion_id_seq CASCADE');
        $this->addSql('DROP TABLE info_champion');
        $this->addSql('DROP INDEX UNIQ_45437EB42547FD14');
        $this->addSql('ALTER TABLE champion DROP info_champion_id');
    }
}
