<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220911091947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invocateur ALTER profile_icon_id DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban1_champion_id DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban2_champion_id DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban3_champion_id DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban4_champion_id DROP NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban5_champion_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invocateur ALTER profile_icon_id SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban1_champion_id SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban2_champion_id SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban3_champion_id SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban4_champion_id SET NOT NULL');
        $this->addSql('ALTER TABLE team ALTER ban5_champion_id SET NOT NULL');
    }
}
