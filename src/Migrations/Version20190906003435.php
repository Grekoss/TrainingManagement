<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906003435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quizzes (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, level VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_94DC9FB5F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizzes_tag (quizzes_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_48EE3252DC68F175 (quizzes_id), INDEX IDX_48EE3252BAD26311 (tag_id), PRIMARY KEY(quizzes_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, text_color VARCHAR(50) NOT NULL, background_color VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quizzes ADD CONSTRAINT FK_94DC9FB5F675F31B FOREIGN KEY (author_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE quizzes_tag ADD CONSTRAINT FK_48EE3252DC68F175 FOREIGN KEY (quizzes_id) REFERENCES quizzes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizzes_tag ADD CONSTRAINT FK_48EE3252BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_user CHANGE first_name first_name VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quizzes_tag DROP FOREIGN KEY FK_48EE3252DC68F175');
        $this->addSql('ALTER TABLE quizzes_tag DROP FOREIGN KEY FK_48EE3252BAD26311');
        $this->addSql('DROP TABLE quizzes');
        $this->addSql('DROP TABLE quizzes_tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('ALTER TABLE app_user CHANGE first_name first_name VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
