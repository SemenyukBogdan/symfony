DROP DATABASE Symfony_project;
CREATE DATABASE Symfony_project;
use Symfony_project;

CREATE TABLE `books`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` TEXT NOT NULL,
    `author_id` BIGINT UNSIGNED NOT NULL,
    `genre_id` BIGINT UNSIGNED NOT NULL,
    `publisher_id` BIGINT UNSIGNED NOT NULL,
    `year` INT NOT NULL,
    `description` BIGINT NOT NULL
);
CREATE TABLE `genres`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` TEXT NOT NULL
);
CREATE TABLE `authors`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `first_name` TEXT NOT NULL,
    `second_name` TEXT NOT NULL,
    `birth_date` DATE NOT NULL,
    `country` TEXT NOT NULL
);
CREATE TABLE `publishers`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` TEXT NOT NULL,
    `country` TEXT NOT NULL,
    `founded_year` INT NOT NULL
);
CREATE TABLE `readers`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `full_name` TEXT NOT NULL,
    `phone` VARCHAR(12) NOT NULL,
    `email` TEXT NOT NULL,
    `registration_date` DATE NOT NULL
);
ALTER TABLE
    `readers` ADD UNIQUE `readers_phone_unique`(`phone`);
CREATE TABLE `librarians`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `full_name` TEXT NOT NULL,
    `position` TEXT NOT NULL,
    `phone` VARCHAR(12) NOT NULL
);
ALTER TABLE
    `librarians` ADD UNIQUE `librarians_phone_unique`(`phone`);
CREATE TABLE `borrowings`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `book_copy_id` BIGINT UNSIGNED NOT NULL,
    `reader_id` BIGINT UNSIGNED NOT NULL,
    `librarian_id` BIGINT UNSIGNED NOT NULL,
    `borrow_date` DATE NOT NULL,
    `due_date` DATE NOT NULL
);
CREATE TABLE `returns`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `borrow_id` BIGINT UNSIGNED NOT NULL,
    `return_date` DATE NOT NULL,
    `condition` TEXT NOT NULL,
    `fine_amount` FLOAT(53) NOT NULL
);
CREATE TABLE `book_copies`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `book_id` BIGINT UNSIGNED NOT NULL,
    `inventory_number` BIGINT NOT NULL,
    `shelf_location` BIGINT NOT NULL,
    `status` TEXT NOT NULL
);
CREATE TABLE `book_reviews`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `book_id` BIGINT UNSIGNED NOT NULL,
    `reader_id` BIGINT UNSIGNED NOT NULL,
    `rating` SMALLINT NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL
);
ALTER TABLE
    `books` ADD CONSTRAINT `books_genre_id_foreign` FOREIGN KEY(`genre_id`) REFERENCES `genres`(`id`);
ALTER TABLE
    `book_copies` ADD CONSTRAINT `book_copies_book_id_foreign` FOREIGN KEY(`book_id`) REFERENCES `books`(`id`);
ALTER TABLE
    `book_reviews` ADD CONSTRAINT `book_reviews_book_id_foreign` FOREIGN KEY(`book_id`) REFERENCES `books`(`id`);
ALTER TABLE
    `books` ADD CONSTRAINT `books_author_id_foreign` FOREIGN KEY(`author_id`) REFERENCES `authors`(`id`);
ALTER TABLE
    `borrowings` ADD CONSTRAINT `borrowings_librarian_id_foreign` FOREIGN KEY(`librarian_id`) REFERENCES `librarians`(`id`);
ALTER TABLE
    `borrowings` ADD CONSTRAINT `borrowings_book_copy_id_foreign` FOREIGN KEY(`book_copy_id`) REFERENCES `book_copies`(`id`);
ALTER TABLE
    `borrowings` ADD CONSTRAINT `borrowings_reader_id_foreign` FOREIGN KEY(`reader_id`) REFERENCES `readers`(`id`);
ALTER TABLE
    `book_reviews` ADD CONSTRAINT `book_reviews_reader_id_foreign` FOREIGN KEY(`reader_id`) REFERENCES `readers`(`id`);
ALTER TABLE
    `returns` ADD CONSTRAINT `returns_borrow_id_foreign` FOREIGN KEY(`borrow_id`) REFERENCES `borrowings`(`id`);
ALTER TABLE
    `books` ADD CONSTRAINT `books_publisher_id_foreign` FOREIGN KEY(`publisher_id`) REFERENCES `publishers`(`id`);