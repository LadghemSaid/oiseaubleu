<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191017222626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55D6DE06A6');
        $this->addSql('DROP INDEX IDX_FC28DA55D6DE06A6 ON voting');
        $this->addSql('ALTER TABLE voting ADD article_id INT DEFAULT NULL, ADD comment_id INT DEFAULT NULL, DROP comment_id_id, DROP article_id_id, CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA557294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_FC28DA55A76ED395 ON voting (user_id)');
        $this->addSql('CREATE INDEX IDX_FC28DA557294869C ON voting (article_id)');
        $this->addSql('CREATE INDEX IDX_FC28DA55F8697D13 ON voting (comment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55A76ED395');
        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA557294869C');
        $this->addSql('ALTER TABLE voting DROP FOREIGN KEY FK_FC28DA55F8697D13');
        $this->addSql('DROP INDEX IDX_FC28DA55A76ED395 ON voting');
        $this->addSql('DROP INDEX IDX_FC28DA557294869C ON voting');
        $this->addSql('DROP INDEX IDX_FC28DA55F8697D13 ON voting');
        $this->addSql('ALTER TABLE voting ADD comment_id_id INT DEFAULT NULL, ADD article_id_id INT DEFAULT NULL, DROP article_id, DROP comment_id, CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE voting ADD CONSTRAINT FK_FC28DA55D6DE06A6 FOREIGN KEY (comment_id_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_FC28DA55D6DE06A6 ON voting (comment_id_id)');
    }
}
