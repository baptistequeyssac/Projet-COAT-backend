<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
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
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('category')
            ->add('events')
            // ->add('user')
            ->add('organizers')
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
