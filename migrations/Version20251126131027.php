<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126131027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33192BA20F');
        $this->addSql('DROP INDEX IDX_CBE5A33192BA20F ON book');
        $this->addSql('ALTER TABLE book CHANGE пgenre_id_id genre_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331C2428192 FOREIGN KEY (genre_id_id) REFERENCES genre (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331C2428192 ON book (genre_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331C2428192');
        $this->addSql('DROP INDEX IDX_CBE5A331C2428192 ON book');
        $this->addSql('ALTER TABLE book CHANGE genre_id_id пgenre_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33192BA20F FOREIGN KEY (пgenre_id_id) REFERENCES genre (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CBE5A33192BA20F ON book (пgenre_id_id)');
    }
}
