<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215150552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE champion_tag (champion_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(champion_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_5F7F9B37FA7FD7EB ON champion_tag (champion_id)');
        $this->addSql('CREATE INDEX IDX_5F7F9B37BAD26311 ON champion_tag (tag_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE champion_tag ADD CONSTRAINT FK_5F7F9B37FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE champion_tag ADD CONSTRAINT FK_5F7F9B37BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('ALTER TABLE champion_tag DROP CONSTRAINT FK_5F7F9B37FA7FD7EB');
        $this->addSql('ALTER TABLE champion_tag DROP CONSTRAINT FK_5F7F9B37BAD26311');
        $this->addSql('DROP TABLE champion_tag');
        $this->addSql('DROP TABLE tag');
    }
}
