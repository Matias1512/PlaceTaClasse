<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLong')
            ->add('nomCourt')
            ->add('controles')
            ->add('modules', EntityType::class, [
                // looks for choices from this entity
                'class' => Module::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'nomLong',
            
                // used to render a select box, check boxes or radios
                 'multiple' => true,
                 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
