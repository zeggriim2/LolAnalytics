<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206223744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Queue';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE queue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE queue (id INT NOT NULL, id_lol INT NOT NULL, map VARCHAR(50) NOT NULL, description VARCHAR(50) DEFAULT NULL, notes VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE queue_id_seq CASCADE');
        $this->addSql('DROP TABLE queue');
    }
}
