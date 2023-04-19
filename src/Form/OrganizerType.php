<?php

namespace App\Form;

use App\Entity\Organizer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('type')
            ->add('description')
            ->add('logo')
            ->add('email')
            ->add('phone')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('events')
            ->add('user')
            ->add('artist')
            ->add('status')
            ->add('region')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organizer::class,
        ]);
    }
}
