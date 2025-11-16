<?php

namespace App\Form;

use App\Entity\BookCopies;
use App\Entity\Borrowings;
use App\Entity\Librarians;
use App\Entity\Readers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('borrowDate')
            ->add('dueDate')
            ->add('bookCopy', EntityType::class, [
                'class' => BookCopies::class,
                'choice_label' => 'id',
            ])
            ->add('reader', EntityType::class, [
                'class' => Readers::class,
                'choice_label' => 'id',
            ])
            ->add('librarian', EntityType::class, [
                'class' => Librarians::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrowings::class,
        ]);
    }
}
