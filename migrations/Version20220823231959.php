<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823231959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, complete VARCHAR(150) NOT NULL, sprite VARCHAR(150) NOT NULL, groupe VARCHAR(100) NOT NULL, x INT NOT NULL, y INT NOT NULL, w INT NOT NULL, h INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN image.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE champion ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB43DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_45437EB43DA5256D ON champion (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB43DA5256D');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP INDEX IDX_45437EB43DA5256D');
        $this->addSql('ALTER TABLE champion DROP image_id');
    }
}
