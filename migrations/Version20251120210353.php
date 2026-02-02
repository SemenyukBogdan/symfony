<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120210353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE returns ADD borrowing_id INT NOT NULL, ADD return_date DATETIME NOT NULL, ADD `condition` VARCHAR(255) NOT NULL, ADD fine_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE returns ADD CONSTRAINT FK_8B164CA54675F064 FOREIGN KEY (borrowing_id) REFERENCES borrowings (id)');
        $this->addSql('CREATE INDEX IDX_8B164CA54675F064 ON returns (borrowing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE returns DROP FOREIGN KEY FK_8B164CA54675F064');
        $this->addSql('DROP INDEX IDX_8B164CA54675F064 ON returns');
        $this->addSql('ALTER TABLE returns DROP borrowing_id, DROP return_date, DROP `condition`, DROP fine_amount');
    }
}
