<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20260210180459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add version Game Data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE game_data_versions (version VARCHAR(100) NOT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE game_data_versions');
    }
}
