<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190919211938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F312469DE2');
        $this->addSql('DROP TABLE category_lesson');
        $this->addSql('DROP INDEX IDX_F87474F312469DE2 ON lesson');
        $this->addSql('ALTER TABLE lesson ADD category VARCHAR(255) NOT NULL, DROP category_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_lesson (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, subtitle VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lesson ADD category_id INT DEFAULT NULL, DROP category');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F312469DE2 FOREIGN KEY (category_id) REFERENCES category_lesson (id)');
        $this->addSql('CREATE INDEX IDX_F87474F312469DE2 ON lesson (category_id)');
    }
}
