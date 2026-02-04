<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260204143704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE game_data_game_modes (game_mode VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(game_mode)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_data_game_types (game_type VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(game_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_data_maps (map_id INT NOT NULL, map_name VARCHAR(100) NOT NULL, notes LONGTEXT DEFAULT NULL, PRIMARY KEY(map_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_data_queues (queue_id INT NOT NULL, map VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, PRIMARY KEY(queue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_data_seasons (id INT NOT NULL, season VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matches ADD game_mode VARCHAR(50) NOT NULL, ADD game_type VARCHAR(50) NOT NULL, ADD map_id INT NOT NULL, ADD queue_id INT NOT NULL');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA7CDF2B87 FOREIGN KEY (game_mode) REFERENCES game_data_game_modes (game_mode)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA67CB3B05 FOREIGN KEY (game_type) REFERENCES game_data_game_types (game_type)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA53C55F64 FOREIGN KEY (map_id) REFERENCES game_data_maps (map_id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA477B5BAE FOREIGN KEY (queue_id) REFERENCES game_data_queues (queue_id)');
        $this->addSql('CREATE INDEX IDX_62615BA7CDF2B87 ON matches (game_mode)');
        $this->addSql('CREATE INDEX IDX_62615BA67CB3B05 ON matches (game_type)');
        $this->addSql('CREATE INDEX IDX_62615BA53C55F64 ON matches (map_id)');
        $this->addSql('CREATE INDEX IDX_62615BA477B5BAE ON matches (queue_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE game_data_game_modes');
        $this->addSql('DROP TABLE game_data_game_types');
        $this->addSql('DROP TABLE game_data_maps');
        $this->addSql('DROP TABLE game_data_queues');
        $this->addSql('DROP TABLE game_data_seasons');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA7CDF2B87');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA67CB3B05');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA53C55F64');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA477B5BAE');
        $this->addSql('DROP INDEX IDX_62615BA7CDF2B87 ON matches');
        $this->addSql('DROP INDEX IDX_62615BA67CB3B05 ON matches');
        $this->addSql('DROP INDEX IDX_62615BA53C55F64 ON matches');
        $this->addSql('DROP INDEX IDX_62615BA477B5BAE ON matches');
        $this->addSql('ALTER TABLE matches DROP game_mode, DROP game_type, DROP map_id, DROP queue_id');
    }
}
