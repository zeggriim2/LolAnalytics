<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214085311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE champion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE champion (id INT NOT NULL, version_id INT DEFAULT NULL, id_lol VARCHAR(35) NOT NULL, key VARCHAR(10) NOT NULL, name VARCHAR(35) NOT NULL, title VARCHAR(150) NOT NULL, lore TEXT NOT NULL, blurb TEXT NOT NULL, partype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_45437EB44BBC2705 ON champion (version_id)');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB44BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE champion_id_seq CASCADE');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB44BBC2705');
        $this->addSql('DROP TABLE champion');
    }
}
