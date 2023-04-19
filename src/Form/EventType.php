<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration')
            ->add('address')
            ->add('price')
            ->add('summary')
            ->add('poster')
            ->add('date')
            ->add('info')
            ->add('frequency')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('artist')
            ->add('type')
            ->add('organizer')
            ->add('region')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
