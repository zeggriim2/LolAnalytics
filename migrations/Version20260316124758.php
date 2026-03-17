<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260316124758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE participant_stats (id INT AUTO_INCREMENT NOT NULL, participant_id INT NOT NULL, cs INT NOT NULL, gold_earned INT NOT NULL, total_damage_dealt_to_champions INT NOT NULL, total_damage_taken INT NOT NULL, vision_score INT NOT NULL, lane VARCHAR(50) NOT NULL, individual_position VARCHAR(50) NOT NULL, summoner1_id INT NOT NULL, summoner2_id INT NOT NULL, champ_level INT NOT NULL, wards_placed INT NOT NULL, wards_killed INT NOT NULL, first_blood_kill TINYINT(1) NOT NULL, items JSON NOT NULL, UNIQUE INDEX UNIQ_96F2CE139D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participant_stats ADD CONSTRAINT FK_96F2CE139D1C3019 FOREIGN KEY (participant_id) REFERENCES participants (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE participant_stats DROP FOREIGN KEY FK_96F2CE139D1C3019');
        $this->addSql('DROP TABLE participant_stats');
    }
}
