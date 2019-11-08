<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108080634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE msalsas_voting_clicks (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, referenceArticle INT NOT NULL, referenceComment INT NOT NULL, user_ip VARCHAR(180) DEFAULT NULL, INDEX IDX_42A654E1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE msalsas_voting_reference_clicks (clicks INT NOT NULL, referenceArticle INT NOT NULL, referenceComment INT NOT NULL, PRIMARY KEY(referenceArticle, referenceComment)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE msalsas_voting_reference_votes (id INT AUTO_INCREMENT NOT NULL, referenceArticle INT DEFAULT NULL, referenceComment INT DEFAULT NULL, positiveVotes INT NOT NULL, negativeVotes INT NOT NULL, userVotes INT NOT NULL, anonymousVotes INT NOT NULL, published INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE msalsas_voting_negative_votes (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, referenceArticle INT DEFAULT NULL, referenceComment INT DEFAULT NULL, user_ip VARCHAR(180) DEFAULT NULL, reason VARCHAR(180) NOT NULL, INDEX IDX_A70D00ABA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE msalsas_voting_positive_votes (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, referenceArticle INT DEFAULT NULL, referenceComment INT DEFAULT NULL, user_ip VARCHAR(180) DEFAULT NULL, INDEX IDX_85A638A7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(64) NOT NULL, username VARCHAR(64) NOT NULL, password VARCHAR(64) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_C2502824E7927C74 (email), UNIQUE INDEX UNIQ_C2502824F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, categorie_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) NOT NULL, status INT DEFAULT NULL, lecture_time INT DEFAULT NULL, text LONGTEXT NOT NULL, INDEX IDX_23A0E66F675F31B (author_id), INDEX IDX_23A0E66BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, article_id INT NOT NULL, text_comment LONGTEXT NOT NULL, created_at DATETIME NOT NULL, approved TINYINT(1) NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE msalsas_voting_clicks ADD CONSTRAINT FK_42A654E1A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE msalsas_voting_negative_votes ADD CONSTRAINT FK_A70D00ABA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE msalsas_voting_positive_votes ADD CONSTRAINT FK_85A638A7A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE msalsas_voting_clicks DROP FOREIGN KEY FK_42A654E1A76ED395');
        $this->addSql('ALTER TABLE msalsas_voting_negative_votes DROP FOREIGN KEY FK_A70D00ABA76ED395');
        $this->addSql('ALTER TABLE msalsas_voting_positive_votes DROP FOREIGN KEY FK_85A638A7A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('DROP TABLE msalsas_voting_clicks');
        $this->addSql('DROP TABLE msalsas_voting_reference_clicks');
        $this->addSql('DROP TABLE msalsas_voting_reference_votes');
        $this->addSql('DROP TABLE msalsas_voting_negative_votes');
        $this->addSql('DROP TABLE msalsas_voting_positive_votes');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE comment');
    }
}
