<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111112814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question ADD questioner_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECC0DE6E1 FOREIGN KEY (questioner_id) REFERENCES questioner (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494ECC0DE6E1 ON question (questioner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECC0DE6E1');
        $this->addSql('DROP INDEX IDX_B6F7494ECC0DE6E1 ON question');
        $this->addSql('ALTER TABLE question DROP questioner_id');
    }
}
