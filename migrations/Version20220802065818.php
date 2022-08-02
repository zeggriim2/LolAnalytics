<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802065818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93ADAABBC2CD822B ON map (map_id_lol)');
        $this->addSql('DROP INDEX uniq_460c35ed53c55f64');
        $this->addSql('CREATE INDEX IDX_460C35ED53C55F64 ON rencontre (map_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_93ADAABBC2CD822B');
        $this->addSql('DROP INDEX IDX_460C35ED53C55F64');
        $this->addSql('CREATE UNIQUE INDEX uniq_460c35ed53c55f64 ON rencontre (map_id)');
    }
}
