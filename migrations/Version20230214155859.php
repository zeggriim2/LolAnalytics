<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214155859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE skin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE champion_skin (champion_id INT NOT NULL, skin_id INT NOT NULL, PRIMARY KEY(champion_id, skin_id))');
        $this->addSql('CREATE INDEX IDX_CE29E9A7FA7FD7EB ON champion_skin (champion_id)');
        $this->addSql('CREATE INDEX IDX_CE29E9A7F404637F ON champion_skin (skin_id)');
        $this->addSql('CREATE TABLE skin (id INT NOT NULL, id_lol VARCHAR(50) NOT NULL, num INT NOT NULL, name VARCHAR(100) NOT NULL, chromas BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE champion_skin ADD CONSTRAINT FK_CE29E9A7FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE champion_skin ADD CONSTRAINT FK_CE29E9A7F404637F FOREIGN KEY (skin_id) REFERENCES skin (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE skin_id_seq CASCADE');
        $this->addSql('ALTER TABLE champion_skin DROP CONSTRAINT FK_CE29E9A7FA7FD7EB');
        $this->addSql('ALTER TABLE champion_skin DROP CONSTRAINT FK_CE29E9A7F404637F');
        $this->addSql('DROP TABLE champion_skin');
        $this->addSql('DROP TABLE skin');
    }
}
