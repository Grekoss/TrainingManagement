<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190911022520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, manager_id INT NOT NULL, student_id INT NOT NULL, date_at DATETIME NOT NULL, rush_of VARCHAR(255) NOT NULL, start_at TIME NOT NULL, stop_at TIME NOT NULL, zone VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, is_responsible TINYINT(1) NOT NULL, goals LONGTEXT DEFAULT NULL, student_comments LONGTEXT NOT NULL, feel_rush INT NOT NULL, INDEX IDX_C42F7784783E3463 (manager_id), INDEX IDX_C42F7784CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784783E3463 FOREIGN KEY (manager_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CB944F1A FOREIGN KEY (student_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_user ADD function VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE report');
        $this->addSql('ALTER TABLE app_user DROP function');
    }
}
