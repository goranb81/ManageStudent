<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161115191440 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exams (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passedexams (id INT AUTO_INCREMENT NOT NULL, exam_id INT DEFAULT NULL, student_id INT DEFAULT NULL, datepass DATE NOT NULL, mark INT NOT NULL, INDEX student_id (student_id), INDEX exam_id (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE students (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, dateofbirth DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE passedexams ADD CONSTRAINT FK_A5892F09578D5E91 FOREIGN KEY (exam_id) REFERENCES exams (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE passedexams ADD CONSTRAINT FK_A5892F09CB944F1A FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE passedexams DROP FOREIGN KEY FK_A5892F09578D5E91');
        $this->addSql('ALTER TABLE passedexams DROP FOREIGN KEY FK_A5892F09CB944F1A');
        $this->addSql('DROP TABLE exams');
        $this->addSql('DROP TABLE passedexams');
        $this->addSql('DROP TABLE students');
        $this->addSql('DROP TABLE users');
    }
}
