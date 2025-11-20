<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120170136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_review ADD book_id INT NOT NULL');
        $this->addSql('ALTER TABLE book_review ADD CONSTRAINT FK_50948A4B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_50948A4B16A2B381 ON book_review (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_review DROP FOREIGN KEY FK_50948A4B16A2B381');
        $this->addSql('DROP INDEX IDX_50948A4B16A2B381 ON book_review');
        $this->addSql('ALTER TABLE book_review DROP book_id');
    }
}
