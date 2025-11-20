<?php

namespace App\Services;

use App\Entity\Author;
use App\Entity\Reader;

class ValidatorService
{


    public function validateAuthor(Author $author) : array
    {
        $errors = [];
        $first_name = $author->getFirstName() ?? '';
        $second_name = $author->getFirstName() ?? '';
        $birth_date = $author->getBirthDate() ?? '';

        if (trim($first_name) === '') {
            $errors[] = 'Імʼя автора не може бути порожнім.';
            return $errors;
        }

        if (mb_strlen($first_name) < 3) {
            $errors[] = 'Імʼя автора повинно містити щонайменше 3 символи.';
            return $errors;
        }
         if (trim($second_name) === '') {
            $errors[] = 'Прізвище автора не може бути порожнім.';
            return $errors;
        }

        if (mb_strlen($second_name) < 3) {
            $errors[] = 'Прізвище автора повинно містити щонайменше 3 символи.';
            return $errors;
        }

        if (preg_match('/\d/', $first_name)) {
            $errors[] = 'Імʼя автора не повинно містити цифри.';
            return $errors;
        }
        if (preg_match('/\d/', $second_name)) {
            $errors[] = 'Прізвище автора не повинно містити цифри.';
            return $errors;
        }

        return $errors;

    }


    public function validateReader(Reader $reader) : array{
        $errors = [];

        $full_name = $reader->getFullName();
        $phone = $reader->getPhone();
        $email = $reader->getEmail();


        if (trim($full_name) === '') {
            $errors[] = 'Імʼя читача не може бути порожнім.';
            return $errors;
        }

        if (mb_strlen($full_name) < 3) {
            $errors[] = 'Імʼя читача повинно містити щонайменше 3 символи.';
            return $errors;
        }

        if (mb_strlen($phone) < 10) {
            $errors[] = 'Номер телефону має містити 10 цифр';
        }
        if (!preg_match('/^\d+$/', $phone)) {
            $errors[] = 'Номер телефону має містити тільки цифри';

        }


        if (trim($email) === '') {
            $errors[] = 'email читача не може бути порожнім.';
            return $errors;
        }

        if (mb_strlen($email) < 6) {
            $errors[] = 'email читача повинно містити щонайменше 6 символів.';
            return $errors;
        }
        if (mb_strlen($email) > 129) {
            $errors[] = 'email читача повинно не може містити більше 128 символів.';
            return $errors;
        }

        return $errors;
    }
}