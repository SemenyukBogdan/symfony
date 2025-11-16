<?php

namespace App\Form;

use App\Entity\Authors;
use App\Entity\Books;
use App\Entity\Genres;
use App\Entity\Publishers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('year')
            ->add('description')
            ->add('author_id', EntityType::class, [
                'class' => Authors::class,
                'choice_label' => 'id',
            ])
            ->add('пgenre_id', EntityType::class, [
                'class' => genres::class,
                'choice_label' => 'id',
            ])
            ->add('publisher_id', EntityType::class, [
                'class' => Publishers::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);
    }
}
