<?php

namespace App\Form;

use App\Entity\Organizer;
use App\Entity\Region;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('events')
            // ->add('user')
            ->add('artist')
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name'
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organizer::class,
        ]);
    }
}
