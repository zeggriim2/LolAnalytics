<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217085812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE spell_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE champion_spell (champion_id INT NOT NULL, spell_id INT NOT NULL, PRIMARY KEY(champion_id, spell_id))');
        $this->addSql('CREATE INDEX IDX_624E9624FA7FD7EB ON champion_spell (champion_id)');
        $this->addSql('CREATE INDEX IDX_624E9624479EC90D ON champion_spell (spell_id)');
        $this->addSql('CREATE TABLE spell (id INT NOT NULL, image_id INT DEFAULT NULL, id_lol VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, tooltip TEXT NOT NULL, cooldown_burn VARCHAR(50) NOT NULL, cost_burn VARCHAR(50) NOT NULL, range JSON NOT NULL, range_burn VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D03FCD8D3DA5256D ON spell (image_id)');
        $this->addSql('ALTER TABLE champion_spell ADD CONSTRAINT FK_624E9624FA7FD7EB FOREIGN KEY (champion_id) REFERENCES champion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE champion_spell ADD CONSTRAINT FK_624E9624479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE spell ADD CONSTRAINT FK_D03FCD8D3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE spell_id_seq CASCADE');
        $this->addSql('ALTER TABLE champion_spell DROP CONSTRAINT FK_624E9624FA7FD7EB');
        $this->addSql('ALTER TABLE champion_spell DROP CONSTRAINT FK_624E9624479EC90D');
        $this->addSql('ALTER TABLE spell DROP CONSTRAINT FK_D03FCD8D3DA5256D');
        $this->addSql('DROP TABLE champion_spell');
        $this->addSql('DROP TABLE spell');
    }
}
