<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115220653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, first_name LONGTEXT NOT NULL, second_name LONGTEXT NOT NULL, birth_date DATE NOT NULL, country LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_copies (id INT AUTO_INCREMENT NOT NULL, book_id_id INT NOT NULL, inventory_number INT NOT NULL, shelf_location LONGTEXT NOT NULL, INDEX IDX_F0A8D81171868B2E (book_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_reviews (id INT AUTO_INCREMENT NOT NULL, reader_id_id INT NOT NULL, rating SMALLINT NOT NULL, comment LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_FA50C399ACFE02D (reader_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, author_id_id INT NOT NULL, пgenre_id_id INT DEFAULT NULL, publisher_id_id INT DEFAULT NULL, title LONGTEXT NOT NULL, year SMALLINT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_4A1B2A9269CCBE9A (author_id_id), INDEX IDX_4A1B2A9292BA20F (пgenre_id_id), INDEX IDX_4A1B2A928AAA43D0 (publisher_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrowings (id INT AUTO_INCREMENT NOT NULL, book_copy_id INT NOT NULL, reader_id INT NOT NULL, librarian_id INT NOT NULL, borrow_date DATE NOT NULL, due_date DATE NOT NULL, INDEX IDX_7547A7B43B550FE4 (book_copy_id), INDEX IDX_7547A7B41717D737 (reader_id), INDEX IDX_7547A7B4D8B58D1F (librarian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE librarians (id INT AUTO_INCREMENT NOT NULL, full_name LONGTEXT NOT NULL, position LONGTEXT NOT NULL, phone VARCHAR(12) NOT NULL, UNIQUE INDEX UNIQ_4C5BCDD2444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishers (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, country LONGTEXT NOT NULL, founded_year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE readers (id INT AUTO_INCREMENT NOT NULL, full_name LONGTEXT NOT NULL, phone VARCHAR(12) NOT NULL, email LONGTEXT NOT NULL, registration_date DATE NOT NULL, UNIQUE INDEX UNIQ_34AD8C05444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE returns (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_copies ADD CONSTRAINT FK_F0A8D81171868B2E FOREIGN KEY (book_id_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE book_reviews ADD CONSTRAINT FK_FA50C399ACFE02D FOREIGN KEY (reader_id_id) REFERENCES readers (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9269CCBE9A FOREIGN KEY (author_id_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9292BA20F FOREIGN KEY (пgenre_id_id) REFERENCES genres (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A928AAA43D0 FOREIGN KEY (publisher_id_id) REFERENCES publishers (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B43B550FE4 FOREIGN KEY (book_copy_id) REFERENCES book_copies (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B41717D737 FOREIGN KEY (reader_id) REFERENCES readers (id)');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B4D8B58D1F FOREIGN KEY (librarian_id) REFERENCES librarians (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_copies DROP FOREIGN KEY FK_F0A8D81171868B2E');
        $this->addSql('ALTER TABLE book_reviews DROP FOREIGN KEY FK_FA50C399ACFE02D');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9269CCBE9A');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9292BA20F');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A928AAA43D0');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B43B550FE4');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B41717D737');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B4D8B58D1F');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE book_copies');
        $this->addSql('DROP TABLE book_reviews');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE borrowings');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE librarians');
        $this->addSql('DROP TABLE publishers');
        $this->addSql('DROP TABLE readers');
        $this->addSql('DROP TABLE returns');
    }
}
