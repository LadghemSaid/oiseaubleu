<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108080447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE msalsas_voting_clicks ADD referenceComment INT NOT NULL, CHANGE reference referenceArticle INT NOT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_reference_clicks DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE msalsas_voting_reference_clicks ADD referenceComment INT NOT NULL, CHANGE reference referenceArticle INT NOT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_reference_clicks ADD PRIMARY KEY (referenceArticle, referenceComment)');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes ADD id INT AUTO_INCREMENT NOT NULL, ADD referenceArticle INT DEFAULT NULL, ADD referenceComment INT DEFAULT NULL, DROP reference');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE msalsas_voting_negative_votes ADD referenceArticle INT DEFAULT NULL, ADD referenceComment INT DEFAULT NULL, DROP reference');
        $this->addSql('ALTER TABLE msalsas_voting_positive_votes ADD referenceArticle INT DEFAULT NULL, ADD referenceComment INT DEFAULT NULL, DROP reference');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE msalsas_voting_clicks ADD reference INT NOT NULL, DROP referenceArticle, DROP referenceComment');
        $this->addSql('ALTER TABLE msalsas_voting_negative_votes ADD reference INT NOT NULL, DROP referenceArticle, DROP referenceComment');
        $this->addSql('ALTER TABLE msalsas_voting_positive_votes ADD reference INT NOT NULL, DROP referenceArticle, DROP referenceComment');
        $this->addSql('ALTER TABLE msalsas_voting_reference_clicks DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE msalsas_voting_reference_clicks ADD reference INT NOT NULL, DROP referenceArticle, DROP referenceComment');
        $this->addSql('ALTER TABLE msalsas_voting_reference_clicks ADD PRIMARY KEY (reference)');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes ADD reference INT NOT NULL, DROP id, DROP referenceArticle, DROP referenceComment');
        $this->addSql('ALTER TABLE msalsas_voting_reference_votes ADD PRIMARY KEY (reference)');
    }
}
