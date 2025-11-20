<?php

namespace App\Form;

use App\Entity\BookCopy;
use App\Entity\Borrowing;
use App\Entity\Librarian;
use App\Entity\Reader;
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
                'class' => BookCopy::class,
                'choice_label' => 'id',
            ])
            ->add('reader', EntityType::class, [
                'class' => Reader::class,
                'choice_label' => 'id',
            ])
            ->add('librarian', EntityType::class, [
                'class' => Librarian::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrowing::class,
        ]);
    }
}
