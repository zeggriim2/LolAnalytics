<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260216151511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE matches ADD version VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BABF1CD3C3 FOREIGN KEY (version) REFERENCES game_data_versions (version)');
        $this->addSql('CREATE INDEX IDX_62615BABF1CD3C3 ON matches (version)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BABF1CD3C3');
        $this->addSql('DROP INDEX IDX_62615BABF1CD3C3 ON matches');
        $this->addSql('ALTER TABLE matches DROP version');
    }
}
