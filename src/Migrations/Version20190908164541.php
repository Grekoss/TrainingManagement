<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190908164541 extends AbstractMigration
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
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, question VARCHAR(255) NOT NULL, prop1 VARCHAR(255) NOT NULL, prop2 VARCHAR(255) NOT NULL, prop3 VARCHAR(255) NOT NULL, prop4 VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A412FA92F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_tag (quiz_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_48B76672853CD175 (quiz_id), INDEX IDX_48B76672BAD26311 (tag_id), PRIMARY KEY(quiz_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, student_id INT NOT NULL, date_at DATETIME NOT NULL, score INT NOT NULL, responses LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_136AC113853CD175 (quiz_id), INDEX IDX_136AC113CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, text_color VARCHAR(50) NOT NULL, background_color VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, phone_number VARCHAR(20) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DECB944F1A FOREIGN KEY (student_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE mentor ADD CONSTRAINT FK_801562DEDB403044 FOREIGN KEY (mentor_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92F675F31B FOREIGN KEY (author_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE quiz_tag ADD CONSTRAINT FK_48B76672853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_tag ADD CONSTRAINT FK_48B76672BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113CB944F1A FOREIGN KEY (student_id) REFERENCES app_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE quiz_tag DROP FOREIGN KEY FK_48B76672853CD175');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113853CD175');
        $this->addSql('ALTER TABLE quiz_tag DROP FOREIGN KEY FK_48B76672BAD26311');
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DECB944F1A');
        $this->addSql('ALTER TABLE mentor DROP FOREIGN KEY FK_801562DEDB403044');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92F675F31B');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113CB944F1A');
        $this->addSql('DROP TABLE mentor');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_tag');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE app_user');
    }
}
