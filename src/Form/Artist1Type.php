<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Artist1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('name')
            ->add('firstName')
            ->add('birthdate')
            ->add('image')
            ->add('bio')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category')
            ->add('events')
            ->add('user')
            ->add('organizers')
            ->add('region')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
