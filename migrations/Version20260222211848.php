<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20260222211848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add admin user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE admin_users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_B4A95E13E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE champion_skins RENAME INDEX idx_champion_skins_champion TO IDX_5372D0A9AF5F8B9BBF1CD3C3');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE admin_users');
        $this->addSql('ALTER TABLE champion_skins RENAME INDEX idx_5372d0a9af5f8b9bbf1cd3c3 TO IDX_champion_skins_champion');
    }
}
