<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823190739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE champion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE info_champion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE champion (id INT NOT NULL, version_id INT DEFAULT NULL, id_name VARCHAR(100) NOT NULL, key VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, partype VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_45437EB44BBC2705 ON champion (version_id)');
        $this->addSql('COMMENT ON COLUMN champion.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE info_champion (id INT NOT NULL, champion_id INT DEFAULT NULL, attack INT NOT NULL, defense INT NOT NULL, magic INT NOT NULL, difficulty INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527605DEFA7FD7EB ON info_champion (champion_id)');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB44BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE info_champion ADD CONSTRAINT FK_527605DEFA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE info_champion DROP CONSTRAINT FK_527605DEFA7FD7EB');
        $this->addSql('DROP SEQUENCE champion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE info_champion_id_seq CASCADE');
        $this->addSql('DROP TABLE champion');
        $this->addSql('DROP TABLE info_champion');
    }
}
