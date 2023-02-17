<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215100315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table passive';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE passive_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE passive (id INT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_76EFFDCF3DA5256D ON passive (image_id)');
        $this->addSql('ALTER TABLE passive ADD CONSTRAINT FK_76EFFDCF3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE champion ADD passive_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE champion ADD CONSTRAINT FK_45437EB46D157422 FOREIGN KEY (passive_id) REFERENCES passive (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_45437EB46D157422 ON champion (passive_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE champion DROP CONSTRAINT FK_45437EB46D157422');
        $this->addSql('DROP SEQUENCE passive_id_seq CASCADE');
        $this->addSql('ALTER TABLE passive DROP CONSTRAINT FK_76EFFDCF3DA5256D');
        $this->addSql('DROP TABLE passive');
        $this->addSql('DROP INDEX IDX_45437EB46D157422');
        $this->addSql('ALTER TABLE champion DROP passive_id');
    }
}
