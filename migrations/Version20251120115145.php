<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120115145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B43B550FE4 FOREIGN KEY (book_copy_id) REFERENCES book_copy (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B41717D737 FOREIGN KEY (reader_id) REFERENCES reader (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B4D8B58D1F FOREIGN KEY (librarian_id) REFERENCES librarian (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B43B550FE4');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B41717D737');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B4D8B58D1F');
    }
}
