<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011062900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, article_id INT NOT NULL, text_comment VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, approved TINYINT(1) NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('DROP TABLE ex2_comment');
        $this->addSql('DROP TABLE ex2_link');
        $this->addSql('DROP TABLE ex2_user');
        $this->addSql('DROP TABLE ex3_wp_posts');
        $this->addSql('DROP TABLE test_strings');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ex2_comment (id INT AUTO_INCREMENT NOT NULL, idLink INT NOT NULL, idUser INT NOT NULL, date DATE NOT NULL, texte VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ex2_link (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, id_comment_last INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ex2_user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(64) NOT NULL COLLATE utf8mb4_general_ci, password VARCHAR(64) NOT NULL COLLATE utf8mb4_general_ci, email VARCHAR(128) NOT NULL COLLATE utf8mb4_general_ci, addresse VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, etat TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ex3_wp_posts (ID BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, post_author BIGINT UNSIGNED DEFAULT 0 NOT NULL, post_date DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_date_gmt DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_content LONGTEXT NOT NULL COLLATE utf8_general_ci, post_title TEXT NOT NULL COLLATE utf8_general_ci, post_excerpt TEXT NOT NULL COLLATE utf8_general_ci, post_status VARCHAR(20) DEFAULT \'publish\' NOT NULL COLLATE utf8_general_ci, comment_status VARCHAR(20) DEFAULT \'open\' NOT NULL COLLATE utf8_general_ci, ping_status VARCHAR(20) DEFAULT \'open\' NOT NULL COLLATE utf8_general_ci, post_password VARCHAR(20) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, post_name VARCHAR(200) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, to_ping TEXT NOT NULL COLLATE utf8_general_ci, pinged TEXT NOT NULL COLLATE utf8_general_ci, post_modified DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_modified_gmt DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_content_filtered LONGTEXT NOT NULL COLLATE utf8_general_ci, post_parent BIGINT UNSIGNED DEFAULT 0 NOT NULL, guid VARCHAR(255) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, menu_order INT DEFAULT 0 NOT NULL, post_type VARCHAR(20) DEFAULT \'post\' NOT NULL COLLATE utf8_general_ci, post_mime_type VARCHAR(100) DEFAULT \'\' NOT NULL COLLATE utf8_general_ci, comment_count BIGINT DEFAULT 0 NOT NULL, INDEX post_parent (post_parent), INDEX type_status_date (post_type, post_status, post_date, ID), INDEX post_author (post_author), INDEX post_name (post_name), PRIMARY KEY(ID)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE test_strings (id INT AUTO_INCREMENT NOT NULL, latin_text TEXT DEFAULT NULL COLLATE latin1_swedish_ci, utf_text TEXT DEFAULT NULL COLLATE utf8_general_ci, comments TEXT DEFAULT NULL COLLATE utf8_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE comment');
    }
}
