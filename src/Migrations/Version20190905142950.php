<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190905142950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mentor (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, mentor_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_801562DECB944F1A (student_id), INDEX IDX_801562DEDB403044 (mentor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DECB944F1A FOREIGN KEY (student_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DEDB403044 FOREIGN KEY (mentor_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9DB403044');
        $this->addSql('DROP INDEX IDX_88BDF3E9DB403044 ON app_user');
        $this->addSql('ALTER TABLE app_user DROP mentor_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mentor');
        $this->addSql('ALTER TABLE app_user ADD mentor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9DB403044 FOREIGN KEY (mentor_id) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_88BDF3E9DB403044 ON app_user (mentor_id)');
    }
}
