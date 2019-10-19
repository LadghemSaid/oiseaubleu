<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191019182157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE msalsas_voting_reference_votes CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_negative_votes CHANGE referenceArticle referenceArticle INT DEFAULT NULL, CHANGE referenceComment referenceComment INT DEFAULT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_positive_votes CHANGE referenceArticle referenceArticle INT DEFAULT NULL, CHANGE referenceComment referenceComment INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE msalsas_voting_negative_votes CHANGE referenceArticle referenceArticle INT NOT NULL, CHANGE referenceComment referenceComment INT NOT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_positive_votes CHANGE referenceArticle referenceArticle INT NOT NULL, CHANGE referenceComment referenceComment INT NOT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes CHANGE id id INT NOT NULL');
    }
}
