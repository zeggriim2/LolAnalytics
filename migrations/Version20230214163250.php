<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214163250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add champ Attack, Defense, Magic and Difficulty in Champion';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE champion ADD attack INT NOT NULL');
        $this->addSql('ALTER TABLE champion ADD defense INT NOT NULL');
        $this->addSql('ALTER TABLE champion ADD magic INT NOT NULL');
        $this->addSql('ALTER TABLE champion ADD difficulty INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE champion DROP attack');
        $this->addSql('ALTER TABLE champion DROP defense');
        $this->addSql('ALTER TABLE champion DROP magic');
        $this->addSql('ALTER TABLE champion DROP difficulty');
    }
}
